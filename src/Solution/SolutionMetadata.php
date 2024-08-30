<?php

declare(strict_types=1);

namespace LLM\Agents\Solution;

use Stringable;

final readonly class SolutionMetadata
{
    public function __construct(
        public MetadataType $type,
        public string $key,
        public string|Stringable|int $content,
    ) {
    }
}
