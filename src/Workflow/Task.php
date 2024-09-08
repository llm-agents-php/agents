<?php

declare(strict_types=1);

namespace LLM\Agents\Workflow;

use LLM\Agents\Solution\MetadataType;
use LLM\Agents\Solution\Solution;
use LLM\Agents\Solution\SolutionMetadata;
use LLM\Agents\Solution\SolutionType;

class Task extends Solution
{
    /** @var array<non-empty-string> */
    private array $dependsOn = [];

    private TaskStatus $status;

    public function __construct(
        string $name,
        string $description,
        public readonly string $primaryCapabilityKey,
        public readonly array $additionalCapabilityKeys = [],
        public readonly ?string $instruction = null,
        public ?string $output = null,
        ?TaskStatus $status = null,
    ) {
        parent::__construct(name: $name, type: SolutionType::Task, description: $description);

        $this->status = $status ?? TaskStatus::Pending;
    }

    public function getInstruction(): ?string
    {
        return $this->instruction;
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

    public function getMemory(): array
    {
        return \array_values(
            \array_filter(
                $this->getMetadata(),
                static fn(SolutionMetadata $metadata): bool => $metadata->type === MetadataType::Memory,
            ),
        );
    }
}
