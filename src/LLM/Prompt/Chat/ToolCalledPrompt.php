<?php

declare(strict_types=1);

namespace LLM\Agents\LLM\Prompt\Chat;

use LLM\Agents\LLM\Prompt\MessageInterface;
use LLM\Agents\LLM\Prompt\SerializableInterface;
use LLM\Agents\LLM\Response\ToolCall;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final readonly class ToolCalledPrompt implements MessageInterface, SerializableInterface
{
    public UuidInterface $uuid;

    /** @param ToolCall[] $tools */
    public function __construct(
        public array $tools = [],
        ?UuidInterface $uuid = null,
    ) {
        $this->uuid = $uuid ?? Uuid::uuid4();
    }

    public function toArray(): array
    {
        return [
            'tools' => \array_map(
                static fn(ToolCall $tool): array => $tool->toArray(),
                $this->tools,
            ),
            'uuid' => $this->uuid->toString(),
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            tools: \array_map(
                static fn(array $tool): ToolCall => ToolCall::fromArray($tool),
                $data['tools'],
            ),
            uuid: Uuid::fromString($data['uuid']),
        );
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }
}