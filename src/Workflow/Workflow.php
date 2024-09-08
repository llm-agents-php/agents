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
}
