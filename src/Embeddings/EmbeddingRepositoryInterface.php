<?php

declare(strict_types=1);

namespace LLM\Agents\Embeddings;

/**
 * This is an abstraction for a repository that can search for similar documents based on embeddings.
 * It can be implemented by a database, a search engine, or any other storage that can handle embeddings.
 */
interface EmbeddingRepositoryInterface
{
    /**
     * Search for documents similar to the given embedding
     *
     * @param Embedding $embedding Vector representation of the document
     * @return Document[]
     */
    public function search(Embedding $embedding, int $limit = 5): array;
}
