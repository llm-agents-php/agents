<?php

declare(strict_types=1);

namespace LLM\Agents\Solution;

final class Model extends Solution
{
    public function __construct(
        public readonly string $model,
    ) {
        parent::__construct(
            name: $model,
            type: SolutionType::Model,
        );
    }
}
