<?php

declare(strict_types=1);

namespace LLM\Agents\LLM\Prompt;

use function array_merge;
use function json_encode;

class DataPrompt implements StringPromptInterface, SerializableInterface
{
    public function __construct(
        protected array $variables = [],
    ) {
    }

    public static function fromArray(array $data): static
    {
        return new static($data);
    }

    /**
     * Creates new prompt with altered values.
     */
    public function withValues(array $values): self
    {
        $prompt = clone $this;
        $prompt->variables = array_merge($this->variables, $values);

        return $prompt;
    }

    public function format(array $variables = []): string
    {
        return json_encode(array_merge($this->variables, $variables));
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
        return $this->variables;
    }
}
