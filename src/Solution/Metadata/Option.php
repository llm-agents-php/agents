<?php

declare(strict_types=1);

namespace LLM\Agents\Solution\Metadata;

use LLM\Agents\Solution\MetadataType;
use LLM\Agents\Solution\SolutionMetadata;

readonly class Option extends SolutionMetadata
{
    public function __construct(
        string $key,
        mixed $content,
    ) {
        parent::__construct(
            type: MetadataType::Configuration,
            key: $key,
            content: $content,
        );
    }
}
