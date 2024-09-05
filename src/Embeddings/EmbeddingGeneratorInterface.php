<?php

declare(strict_types=1);

namespace LLM\Agents\Embeddings;

interface EmbeddingGeneratorInterface
{
    public function generate(Document ...$document): array;
}
