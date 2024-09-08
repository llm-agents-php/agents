<?php

declare(strict_types=1);

namespace LLM\Agents\Workflow;

final class Workflow
{
    /** @var array<Task> */
    private array $tasks = [];
    private ?Loop $loop = null;

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

    public function setLoop(Loop $loop): self
    {
        $this->loop = $loop;
        return $this;
    }

    public function getLoop(): ?Loop
    {
        return $this->loop;
    }

    public function getTasks(): array
    {
        return $this->tasks;
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
