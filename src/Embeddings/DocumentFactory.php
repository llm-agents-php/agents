<?php

declare(strict_types=1);

namespace LLM\Agents\Embeddings;

use LLM\Agents\Embeddings\Source\FileSource;

class DocumentFactory
{
    public function createFromText(string $content, Source $source = new NullSource()): Document
    {
        return new Document($content, $source);
    }

    public function createFromPath(string $path): Document
    {
        return new Document(\file_get_contents($path), new FileSource($path));
    }
}
