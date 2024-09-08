<?php

declare(strict_types=1);

namespace LLM\Agents\Workflow;

use LLM\Agents\Agent\Exception\AgentNotFoundException;
use LLM\Agents\AgentExecutor\ExecutorInterface;
use LLM\Agents\Solution\SolutionMetadata;
use LLM\Agents\Workflow\Exception\MissingDependencyException;

final class WorkflowExecutor
{
    /** @var array<non-empty-string, TaskResult> */
    private array $results = [];

    public function __construct(
        private readonly TaskRouter $taskRouter,
        private readonly ExecutorInterface $agentExecutor,
        private int $maxRecursionDepth = 10,
    ) {}

    public function execute(Workflow $workflow, string $userInput): WorkflowResult
    {
        if (!$workflow->validateDependencies()) {
            throw new MissingDependencyException('Workflow has missing dependencies');
        }

        $context = new WorkflowContext(userInput: $userInput);
        $this->executeTasks($workflow->getTasks(), $workflow, $context);

        return new WorkflowResult($workflow, $this->results);
    }

    private function executeTasks(array $tasks, Workflow $workflow, WorkflowContext $context): void
    {
        foreach ($tasks as $task) {
            try {
                if ($task instanceof DecisionTask) {
                    if ($context->getExplorationDepth() >= $this->maxRecursionDepth) {
                        $this->results[$task->name] = new TaskResult(
                            $task,
                            TaskStatus::Completed,
                            'Max recursion depth reached',
                        );
                        continue;
                    }

                    $decision = $this->makeDecision($task, $context);
                    $branch = $workflow->getBranch($task->name, $decision);
                    if ($branch) {
                        $context->incrementExplorationDepth();
                        $this->executeTasks($branch->getTasks(), $workflow, $context);
                    }
                } else {
                    $this->processTask($task, $context);
                }
            } catch (\Exception $e) {
                $this->results[$task->name] = new TaskResult($task, TaskStatus::Failed, $e->getMessage());
            }
        }
    }

    private function makeDecision(DecisionTask $task, WorkflowContext $context): string
    {
        $retries = 3;

        do {
            $result = $this->executeTask(
                $task,
                $context->withInstruction($task->getInstruction()),
            );

            // Ensure the decision is one of the possible outcomes
            if ($task->isOutcomeValid($result)) {
                return $result;
            }

            $retries--;
        } while ($retries > 0);

        throw new \RuntimeException('Failed to make a valid decision');
    }

    private function processTask(Task $task, WorkflowContext $context): void
    {
        if (!$this->areDependenciesMet($task)) {
            throw new MissingDependencyException("Dependencies not met for task: " . $task->name);
        }

        $task->setStatus(TaskStatus::InProgress);
        $context = $this->buildTaskContext($task, $context);

        try {
            $result = $this->executeTask($task, $context->withInstruction($task->getInstruction()));
            $this->results[$task->name] = new TaskResult($task, TaskStatus::Completed, $result);
            $context->addValues([$task->name . '_result' => $result]);
            $task->setStatus(TaskStatus::Completed);
        } catch (\Exception $e) {
            $this->results[$task->name] = new TaskResult($task, TaskStatus::Failed, $e->getMessage());
            $task->setStatus(TaskStatus::Failed);
        }
    }

    private function areDependenciesMet(Task $task): bool
    {
        foreach ($task->getDependsOn() as $dependency) {
            if (!isset($this->results[$dependency]) || $this->results[$dependency]->status !== TaskStatus::Completed) {
                return false;
            }
        }
        return true;
    }

    private function buildTaskContext(Task $task, WorkflowContext $context): WorkflowContext
    {
        foreach ($task->getDependsOn() as $dependency) {
            $context->addValues([$dependency . '_result' => $this->results[$dependency]->result]);
        }

        return $context;
    }

    private function executeTask(Task $task, WorkflowContext $context): string
    {
        $agent = $this->taskRouter->routeTask($task);
        if (!$agent) {
            throw new AgentNotFoundException("No suitable agent found for task: " . $task->name);
        }

        return $this->agentExecutor->execute(
            agent: $agent->getKey(),
            prompt: $context,
            promptContext: $context->withValues([
                'dynamic_memory' => \implode(
                    PHP_EOL,
                    \array_map(
                        static fn(SolutionMetadata $metadata) => $metadata->content,
                        $task->getMemory(),
                    ),
                ),
            ]),
        )->result->content;
    }
}
