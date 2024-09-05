<?php

declare(strict_types=1);

namespace LLM\Agents\Embeddings;

interface EmbeddingRepositoryInterface
{
    /**
     * @param array $embedding Vector representation of the document
     * @return Document[]
     */
    public function search(array $embedding, int $limit = 5): array;
}
