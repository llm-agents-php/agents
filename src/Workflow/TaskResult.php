<?php

declare(strict_types=1);

namespace LLM\Agents\Workflow;

final readonly class TaskResult
{
    public function __construct(
        public Task $task,
        public TaskStatus $status,
        public string $result,
    ) {}
}
