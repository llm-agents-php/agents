<?php

declare(strict_types=1);

namespace LLM\Agents\Embeddings;

readonly class Embedding implements \Countable, \JsonSerializable
{
    public function __construct(
        public array $vector,
    ) {}

    public function size(): int
    {
        return \count($this->vector);
    }

    public function count(): int
    {
        return $this->size();
    }

    public function jsonSerialize(): array
    {
        return $this->vector;
    }
}
