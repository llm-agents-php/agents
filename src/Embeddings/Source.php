<?php

declare(strict_types=1);

namespace LLM\Agents\Embeddings;

/**
 * Source of a document. It can be a file, a database, etc.
 * Metadata can be used to store additional information like the path of the file or the database name.
 */
readonly class Source implements \JsonSerializable
{
    public function __construct(
        public string $type,
        public array $metadata = [],
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'type' => $this->type,
            'metadata' => $this->metadata,
        ];
    }
}
