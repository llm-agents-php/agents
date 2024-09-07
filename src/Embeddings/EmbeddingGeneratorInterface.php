<?php

declare(strict_types=1);

namespace LLM\Agents\Embeddings;

interface EmbeddingGeneratorInterface
{
    /**
     * Generate embeddings for the given documents.
     * The embeddings will be injected into the documents.
     *
     * @param Document ...$documents
     * @return array<Document> List of documents with embeddings
     */
    public function generate(Document ...$documents): array;
}
