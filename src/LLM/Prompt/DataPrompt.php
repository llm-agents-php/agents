<?php

declare(strict_types=1);

namespace LLM\Agents\LLM\Prompt;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class DataPrompt implements StringPromptInterface, SerializableInterface
{
    public readonly UuidInterface $uuid;

    public function __construct(
        protected array $variables = [],
        ?UuidInterface $uuid = null,
    ) {
        $this->uuid = $uuid ?? Uuid::uuid4();
    }

    /**
     * Creates new prompt with altered values.
     */
    public function withValues(array $values): self
    {
        $prompt = clone $this;
        $prompt->variables = \array_merge($this->variables, $values);

        return $prompt;
    }

    public function format(array $variables = []): string
    {
        return \json_encode(\array_merge($this->variables, $variables));
    }

    public function __toString(): string
    {
        return $this->format();
    }

    /**
     * Serializes prompt to array.
     */
    public function toArray(): array
    {
        return [
            'variables' => $this->variables,
            'uuid' => $this->uuid->toString(),
        ];
    }

    public static function fromArray(array $data): static
    {
        return new static(
            variables: $data['variables'],
            uuid: Uuid::fromString($data['uuid']),
        );
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }
}
