<?php

declare(strict_types=1);

namespace LLM\Agents\AgentExecutor;

use LLM\Agents\Agent\Execution;

/**
 * @internal
 */
final readonly class InterceptorHandler
{
    public function __construct(
        private ExecutorInterface $executor,
    ) {}

    public function __invoke(ExecutionInput $input): Execution
    {
        return $this->executor->execute(
            agent: $input->agent,
            prompt: $input->prompt,
            context: $input->context,
            options: $input->options,
            promptContext: $input->promptContext,
        );
    }
}
