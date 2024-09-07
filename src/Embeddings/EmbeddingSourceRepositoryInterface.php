<?php

declare(strict_types=1);

namespace LLM\Agents\Embeddings;

use LLM\Agents\Embeddings\Exception\EmbeddingSourceNotFoundException;

/**
 * This is an abstraction for retrieving embedding sources like Vector databases.
 */
interface EmbeddingSourceRepositoryInterface
{
    /**
     * Retrieve an embedding source by name
     *
     * @param non-empty-string $sourceName The name of the source
     * @throws EmbeddingSourceNotFoundException
     */
    public function get(string $sourceName): EmbeddingRepositoryInterface;
}
