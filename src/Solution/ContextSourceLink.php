<?php

declare(strict_types=1);

namespace LLM\Agents\Solution;

/**
 * Represents a context solution that links to an embeddings source, like vector databases.
 */
final class ContextSourceLink extends Solution
{
    public function __construct(
        string $name,
        ?string $description = null,
    ) {
        parent::__construct(
            name: $name,
            type: SolutionType::Context,
            description: $description,
        );
    }

    public function getName(): string
    {
        return $this->name;
    }
}
