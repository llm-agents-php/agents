<?php

declare(strict_types=1);

namespace LLM\Agents\Workflow;

final class WorkflowContext implements \Stringable
{
    private array $context = [];
    private ?string $instruction = null;

    public function __construct(
        private readonly string $userInput,
    ) {}

    public function withInstruction(string $instruction): self
    {
        $self = clone $this;
        $self->instruction = $instruction;

        return $self;
    }

    public function add(string $key, mixed $value): void
    {
        $this->context[$key] = $value;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->context[$key] ?? $default;
    }

    public function has(string $key): bool
    {
        return isset($this->context[$key]);
    }

    public function getUserInput(): string
    {
        return $this->userInput;
    }

    public function __toString(): string
    {
        return \sprintf(
            <<<'PROMPT'
%s
User input:
%s
Context:
%s
PROMPT,
            $this->instruction ? \sprintf('Instruction: %s', $this->instruction) : '',
            $this->userInput,
            \json_encode($this->context),
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'user_input' => $this->userInput,
            'context' => $this->context,
        ];
    }
}
