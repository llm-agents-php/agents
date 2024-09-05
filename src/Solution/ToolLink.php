<?php

declare(strict_types=1);

namespace LLM\Agents\Solution;

/**
 * A tool link is a solution that points to a tool that can be used to solve a problem.
 */
class ToolLink extends Solution
{
    public function __construct(
        string $name,
        ?string $description = null,
    ) {
        parent::__construct(
            name: $name,
            type: SolutionType::Tool,
            description: $description,
        );
    }

    public function getName(): string
    {
        return $this->name;
    }
}
