<?php

declare(strict_types=1);

namespace LLM\Agents\Workflow;

final readonly class Branch
{
    /**
     * @param non-empty-string $name
     * @param array<Task> $tasks
     */
    public function __construct(
        public string $name,
        public array $tasks,
    ) {}
}
