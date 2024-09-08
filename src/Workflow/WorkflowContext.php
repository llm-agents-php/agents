<?php

declare(strict_types=1);

namespace LLM\Agents\Workflow;

final class WorkflowContext implements \Stringable
{
    private array $context = [];

    public function __construct(
        private readonly string $userInput,
    ) {}

    public function add(string $key, mixed $value): void
    {
        $this->context[$key] = $value;
    }

    public function __toString(): string
    {
        return \sprintf(
            <<<'PROMPT'
User input:
%s
Context:
%s
PROMPT,
            $this->userInput,
            \json_encode($this->context),
        );
    }
}
