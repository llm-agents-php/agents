<?php

declare(strict_types=1);

namespace LLM\Agents\Agent;

use LLM\Agents\Solution\SolutionMetadata;

interface MetadataAwareInterface
{
    public function getMetadata(): array;

    public function addMetadata(SolutionMetadata ...$metadata): void;
}
