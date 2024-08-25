<?php

declare(strict_types=1);

namespace LLM\Agents\Agent;

use LLM\Agents\Solution\Solution;
use LLM\Agents\Solution\SolutionType;

final class Agent extends Solution
{
    public function __construct(
        public readonly string $key,
        string $name,
        string $description,
        public readonly string $instruction,
        public bool $isActive = true,
    ) {
        parent::__construct(
            name: $name,
            type: SolutionType::Agent,
            description: $description,
        );
    }
}
