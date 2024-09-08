<?php

declare(strict_types=1);

namespace LLM\Agents\Workflow;

class Task
{
    /** @var array<non-empty-string> */
    private array $dependsOn = [];

    private TaskStatus $status;

    public function __construct(
        public readonly string $name,
        public readonly string $description,
        public readonly string $primaryCapabilityKey,
        public readonly array $additionalCapabilityKeys = [],
        public ?string $output = null,
        ?TaskStatus $status = null,
    ) {
        $this->status = $status ?? TaskStatus::Pending;
    }

    public function addDependency(string $taskName): self
    {
        $this->dependsOn[] = $taskName;
        return $this;
    }

    public function withOutput(string $output): self
    {
        $this->output = $output;
        return $this;
    }

    public function getStatus(): TaskStatus
    {
        return $this->status;
    }

    public function setStatus(TaskStatus $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getDependsOn(): array
    {
        return $this->dependsOn;
    }

    public function isCompleted(): bool
    {
        return $this->status === TaskStatus::Completed;
    }
}
