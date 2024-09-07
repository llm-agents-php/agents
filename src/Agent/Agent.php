<?php

declare(strict_types=1);

namespace LLM\Agents\Agent;

use LLM\Agents\Solution\Solution;
use LLM\Agents\Solution\SolutionType;

final class Agent extends Solution
{
    /**
     * @param string $key The unique key of the agent that is used to retrieve it
     * @param string $name The name of the agent
     * @param string $description The short description
     * @param string $instruction The initial instruction for the agent to interact with the user
     * @param bool $isActive
     */
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
