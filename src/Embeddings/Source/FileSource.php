<?php

declare(strict_types=1);

namespace LLM\Agents\Embeddings\Source;

use LLM\Agents\Embeddings\Source;

readonly class FileSource extends Source
{
    public function __construct(
        string $path,
    ) {
        parent::__construct('local_file', ['path' => $path]);
    }
}
