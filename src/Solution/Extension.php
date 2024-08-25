<?php

declare(strict_types=1);

namespace LLM\Agents\Solution;

final class Extension extends Solution
{
    public function __construct(
        string $name,
        string $description,
    ) {
        parent::__construct(
            name: $name,
            type: SolutionType::Extension,
            description: $description,
        );
    }
}
