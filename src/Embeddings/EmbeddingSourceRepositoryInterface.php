<?php

declare(strict_types=1);

namespace LLM\Agents\Embeddings;

use LLM\Agents\Embeddings\Exception\EmbeddingSourceNotFoundException;

interface EmbeddingSourceRepositoryInterface
{
    /**
     * @param non-empty-string $sourceName The name of the source
     * @throws EmbeddingSourceNotFoundException
     */
    public function get(string $sourceName): EmbeddingRepositoryInterface;
}
