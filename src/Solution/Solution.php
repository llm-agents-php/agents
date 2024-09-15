<?php

declare(strict_types=1);

namespace LLM\Agents\Solution;

use LLM\Agents\Agent\MetadataAwareInterface;

abstract class Solution implements MetadataAwareInterface
{
    /**
     * @var array<SolutionMetadata>
     */
    private array $metadata = [];

    public function __construct(
        public readonly string $name,
        public readonly SolutionType $type,
        public readonly ?string $description = null,
    ) {}

    public function addMetadata(SolutionMetadata ...$metadata): void
    {
        foreach ($metadata as $meta) {
            $this->metadata[] = $meta;
        }
    }

    public function getMetadata(): array
    {
        return $this->metadata;
    }
}
