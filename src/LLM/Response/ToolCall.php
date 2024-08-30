<?php

declare(strict_types=1);

namespace LLM\Agents\LLM\Response;

use LLM\Agents\LLM\Prompt\SerializableInterface;

final class ToolCall implements SerializableInterface
{
    public function __construct(
        public string $id,
        public string $name,
        public string $arguments,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            name: $data['name'],
            arguments: $data['arguments'],
        );
    }

    public function withArguments(string $arguments): self
    {
        return new self($this->id, $this->name, $this->arguments . $arguments);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'arguments' => $this->arguments,
        ];
    }
}
