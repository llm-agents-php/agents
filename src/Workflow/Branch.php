<?php

declare(strict_types=1);

namespace LLM\Agents\Workflow;

final class Branch
{
    /**
     * @param non-empty-string $name
     * @param array<Task> $tasks
     */
    public function __construct(
        public readonly string $name,
        private array $tasks,
    ) {}

    public function addTask(Task $task): self
    {
        $this->tasks[] = $task;
        return $this;
    }

    public function getTasks(): array
    {
        return $this->tasks;
    }
}
