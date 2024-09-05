<?php

declare(strict_types=1);

namespace LLM\Agents\Embeddings;

interface EmbeddingSourceRegistryInterface
{
    public function register(string $name, EmbeddingRepositoryInterface $agent): void;
}
