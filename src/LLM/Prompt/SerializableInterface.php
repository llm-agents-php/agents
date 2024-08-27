<?php

declare(strict_types=1);

namespace LLM\Agents\LLM\Prompt;

interface SerializableInterface
{
    /**
     * Unpacks prompt from array.
     */
    public static function fromArray(array $data): self;

    /**
     * Serializes prompt to array.
     */
    public function toArray(): array;
}
