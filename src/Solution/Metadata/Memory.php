<?php

declare(strict_types=1);

namespace LLM\Agents\Solution\Metadata;

use LLM\Agents\Solution\MetadataType;
use LLM\Agents\Solution\SolutionMetadata;

/**
 * Add some memory to an agent.
 */
final readonly class Memory extends SolutionMetadata
{
    public function __construct(
        \Stringable|int|string $content,
        ?string $key = 'memory',
    ) {
        parent::__construct(
            type: MetadataType::Memory,
            key: $key,
            content: $content,
        );
    }
}
