<?php

declare(strict_types=1);

namespace LLM\Agents\Embeddings;

final readonly class NullSource extends Source
{
    public function __construct()
    {
        parent::__construct('null');
    }
}
