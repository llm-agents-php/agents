<?php

declare(strict_types=1);

namespace LLM\Agents\Solution;

final class Capability extends Solution
{
    public function __construct(
        string $key,
        ?string $description = null,
    ) {
        parent::__construct(
            name: $key,
            type: SolutionType::Capability,
            description: $description,
        );
    }
}
