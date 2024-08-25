<?php

declare(strict_types=1);

namespace LLM\Agents\LLM\Prompt\Chat;

use LLM\Agents\LLM\Prompt\MessageInterface;
use LLM\Agents\LLM\Prompt\SerializableInterface;
use LLM\Agents\LLM\Response\ToolCall;

final class ToolCalledPrompt implements MessageInterface, SerializableInterface
{
    /** @param ToolCall[] $tools */
    public function __construct(
        public array $tools = [],
    ) {}

    public function toArray(): array
    {
        return [
            'tools' => \array_map(
                static fn(ToolCall $tool): array => $tool->toArray(),
                $this->tools,
            ),
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            tools: \array_map(
                static fn(array $tool): ToolCall => ToolCall::fromArray($tool),
                $data['tools'],
            ),
        );
    }
}