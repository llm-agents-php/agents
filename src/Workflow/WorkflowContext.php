<?php

declare(strict_types=1);

namespace LLM\Agents\Workflow;

use LLM\Agents\LLM\PromptContextInterface;

final class WorkflowContext implements \Stringable, PromptContextInterface
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

    public function add(string $key, mixed $value): void {}

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->context[$key] ?? $default;
    }

    public function has(string $key): bool
    {
        return isset($this->context[$key]);
    }

    public function __toString(): string
    {
        return \sprintf(
            <<<'PROMPT'
%s
User input:
%s
PROMPT,
            $this->instruction ? \sprintf('Instruction: %s', $this->instruction) : '',
            $this->userInput,
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'user_input' => $this->userInput,
            'context' => $this->context,
        ];
    }

    public function addValues(array $values): static
    {
        $this->context = \array_merge($this->context, $values);

        return $this;
    }

    public function getValues(): array
    {
        return $this->context;
    }
}
