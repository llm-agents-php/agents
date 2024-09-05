<?php

declare(strict_types=1);

namespace LLM\Agents\Embeddings;

readonly class Source
{
    public function __construct(
        public string $type,
        public array $metadata = [],
    ) {}
}
