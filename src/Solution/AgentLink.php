<?php

declare(strict_types=1);

namespace LLM\Agents\Solution;

/**
 * Represents a link to another agent.
 */
class AgentLink extends Solution
{
    public function __construct(
        string $name,
        /** @var class-string */
        public string $outputSchema,
        ?string $description = null,
    ) {
        parent::__construct(
            name: $name,
            type: SolutionType::Agent,
            description: $description,
        );
    }

    public function getName(): string
    {
        return $this->name;
    }
}
