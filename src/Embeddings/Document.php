<?php

declare(strict_types=1);

namespace LLM\Agents\Embeddings;

class Document implements \Stringable
{
    /** @var Embedding|null */
    private ?Embedding $embedding;
    public readonly string $hash;
    public readonly int $length;

    public function __construct(
        public readonly string $content,
        public readonly Source $source = new NullSource(),
    ) {
        $this->embedding = null;
        $this->hash = \md5($content);
        $this->length = \mb_strlen($content);
    }

    final public function withEmbedding(Embedding $embedding): self
    {
        $new = clone $this;
        $new->embedding = $embedding;

        return $new;
    }

    final public function hasEmbedding(): bool
    {
        return $this->embedding !== null;
    }

    final public function getEmbedding(): ?Embedding
    {
        return $this->embedding;
    }

    public function __toString(): string
    {
        return $this->content;
    }

    public function isEquals(Document $document): bool
    {
        return $this->hash === $document->hash;
    }
}
