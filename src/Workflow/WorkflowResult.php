<?php

declare(strict_types=1);

namespace LLM\Agents\Workflow;

final readonly class WorkflowResult
{
    public function __construct(
        public Workflow $workflow,
        /** @var array<string, TaskResult> */
        public array $taskResults,
    ) {}

    public function isSuccessful(): bool
    {
        return !\in_array(TaskStatus::Failed, \array_map(fn($tr): TaskStatus => $tr->status, $this->taskResults));
    }
}
