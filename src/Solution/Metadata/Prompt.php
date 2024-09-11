<?php

declare(strict_types=1);

namespace LLM\Agents\Solution\Metadata;

use LLM\Agents\Solution\MetadataType;
use LLM\Agents\Solution\SolutionMetadata;

/**
 * Add a pre-defined prompt to an agent.
 */
final readonly class Prompt extends SolutionMetadata
{
    public function __construct(
        \Stringable|string $content,
        ?string $key = 'prompt',
    ) {
        parent::__construct(
            type: MetadataType::Prompt,
            key: $key,
            content: $content,
        );
    }
}
