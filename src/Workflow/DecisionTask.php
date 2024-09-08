<?php

declare(strict_types=1);

namespace LLM\Agents\Workflow;

class DecisionTask extends Task
{
    public function __construct(
        string $name,
        string $description,
        string $instruction,
        public readonly array $possibleOutcomes,
    ) {
        parent::__construct(
            name: $name,
            description: $description,
            primaryCapabilityKey: 'decision_making',
            instruction: $instruction,
        );
    }

    public function getInstruction(): string
    {
        return \sprintf(
            <<<'PROMPT'
There are multiple possible outcomes for this decision task:
%s
Please choose one of the above outcomes and write it. Don not write anything except the outcome. Only one outcome is allowed.
PROMPT,
            \implode(', ', $this->possibleOutcomes),
        );
    }

    public function isOutcomeValid(string $outcome): bool
    {
        $outcome = \trim($outcome);

        return \in_array($outcome, $this->possibleOutcomes, true);
    }
}
