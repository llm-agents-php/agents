<?php

declare(strict_types=1);

namespace LLM\Agents\Embeddings;

interface EmbeddingSourceRegistryInterface
{
    /**
     * Register a new embedding source, like Vector database.
     */
    public function register(string $name, EmbeddingRepositoryInterface $repository): void;
}
