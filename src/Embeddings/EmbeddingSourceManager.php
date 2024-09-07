<?php

declare(strict_types=1);

namespace LLM\Agents\Embeddings;

use LLM\Agents\Embeddings\Exception\EmbeddingSourceNotFoundException;

final class EmbeddingSourceManager implements EmbeddingSourceRegistryInterface, EmbeddingSourceRepositoryInterface
{
    /** @var array<non-empty-string, EmbeddingRepositoryInterface> */
    private array $sources = [];

    public function register(string $name, EmbeddingRepositoryInterface $repository): void
    {
        $this->sources[$name] = $repository;
    }

    public function get(string $sourceName): EmbeddingRepositoryInterface
    {
        if (!isset($this->sources[$sourceName])) {
            throw new EmbeddingSourceNotFoundException(\sprintf('Embedding source "%s" not found', $sourceName));
        }

        return $this->sources[$sourceName];
    }
}
