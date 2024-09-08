<?php

declare(strict_types=1);

namespace LLM\Agents\Workflow;

final readonly class Loop
{
    public function __construct(
        public string $iteratorVariable,
        public Task $task,
        public string $combineFunction,
        public int $maxIterations = 3,
    ) {}

    public function execute(WorkflowContext $context, callable $executeTask): array
    {
        $results = [];
        $iterations = 0;

        while ($iterations < $this->maxIterations) {
            $iterationContext = clone $context;
            $iterationContext->add($this->iteratorVariable, $iterations);

            $result = $executeTask($this->task, $iterationContext);
            $results[] = $result;

            if ($this->shouldStopLoop($result)) {
                break;
            }

            $iterations++;
        }

        return $this->combineResults($results);
    }

    private function shouldStopLoop(string $result): bool
    {
        // Implement loop termination logic here
        return false;
    }

    private function combineResults(array $results): array
    {
        // Implement result combination logic here
        return $results;
    }
}
