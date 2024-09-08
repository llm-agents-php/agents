<?php

declare(strict_types=1);

namespace LLM\Agents\Solution;

abstract class Solution
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
        foreach ($metadata as $metadatum) {
            $this->metadata[] = $metadata;
        }
    }

    public function getMetadata(): array
    {
        return $this->metadata;
    }
}
