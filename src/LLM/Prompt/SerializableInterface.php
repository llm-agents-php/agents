<?php

declare(strict_types=1);

namespace LLM\Agents\LLM\Prompt;

interface SerializableInterface
{
    /**
     * Serializes prompt to array.
     */
    public function toArray(): array;

    /**
     * Unpacks prompt from array.
     */
    public static function fromArray(array $data): self;
}