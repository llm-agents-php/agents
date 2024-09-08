<?php

declare(strict_types=1);

namespace LLM\Agents\Workflow;

final readonly class Loop
{
    public function __construct(
        public string $iteratorVariable,
        public Task $task,
        public string $combineFunction,
    ) {}
}
