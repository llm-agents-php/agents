<?php

declare(strict_types=1);

namespace LLM\Agents\Workflow;

use LLM\Agents\AgentExecutor\ExecutorInterface;

final class WorkflowExecutor
{
    private array $results = [];

    public function __construct(
        private readonly TaskRouter $taskRouter,
        private readonly ExecutorInterface $agentExecutor,
    ) {}

    public function execute(Workflow $workflow, string $userInput): array
    {
        $context = new WorkflowContext(userInput: $userInput);

        foreach ($workflow->getTasks() as $task) {
            if (!$this->areDependenciesMet($task)) {
                throw new \RuntimeException("Dependencies not met for task: " . $task->name);
            }

            $context = $this->buildTaskContext($task, $context);

            $this->results[$task->name] = $this->executeTask($task, $context);
        }

        return $this->results;
    }

    private function areDependenciesMet(Task $task): bool
    {
        foreach ($task->getDependsOn() as $dependency) {
            if (!isset($this->results[$dependency])) {
                return false;
            }
        }
        return true;
    }

    private function buildTaskContext(Task $task, WorkflowContext $context): WorkflowContext
    {
        foreach ($task->getDependsOn() as $dependency) {
            $context->add($dependency . '_result', $this->results[$dependency]);
        }

        return $context;
    }

    private function executeTask(Task $task, WorkflowContext $context): string
    {
        $agent = $this->taskRouter->routeTask($task);
        if (!$agent) {
            throw new \RuntimeException("No suitable agent found for task: " . $task->name);
        }

        return $this->agentExecutor->execute($agent->getKey(), $context)->result->content;
    }
}
