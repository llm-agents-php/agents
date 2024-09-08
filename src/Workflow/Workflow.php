<?php

declare(strict_types=1);

namespace LLM\Agents\Workflow;

final class Workflow
{
    /** @var array<Task> */
    private array $tasks = [];
    /** @var array<Branch> */
    private array $branches = [];

    public function __construct(
        public readonly string $name,
        public readonly string $description,
    ) {}

    public function getTask(string $name): ?Task
    {
        return $this->tasks[$name] ?? null;
    }

    public function addTask(Task $task): self
    {
        $this->tasks[] = $task;
        return $this;
    }

    public function getTasks(): array
    {
        return $this->tasks;
    }

    public function addRecursiveBranch(DecisionTask $decisionTask, Branch ...$branches): self
    {
        $this->addBranch($decisionTask, ...$branches);

        // Add the decision task to the end of each branch to create recursion
        foreach ($branches as $branch) {
            $branch->addTask($decisionTask);
        }

        return $this;
    }

    public function addBranch(DecisionTask $decisionTask, Branch ...$branches): self
    {
        $this->tasks[] = $decisionTask;

        foreach ($branches as $branch) {
            $this->branches[$decisionTask->name][$branch->name] = $branch;
        }
        return $this;
    }

    public function getBranch(string $decisionTaskName, string $branchName): ?Branch
    {
        return $this->branches[$decisionTaskName][$branchName] ?? null;
    }

    public function validateDependencies(): bool
    {
        foreach ($this->tasks as $task) {
            foreach ($task->getDependsOn() as $dependencyName) {
                if (!isset($this->tasks[$dependencyName])) {
                    return false;
                }
            }
        }
        return true;
    }
}
