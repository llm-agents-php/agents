<?php

declare(strict_types=1);

namespace LLM\Agents\AgentExecutor\Interceptor;

use LLM\Agents\Agent\AgentRepositoryInterface;
use LLM\Agents\Agent\Execution;
use LLM\Agents\AgentExecutor\ExecutionInput;
use LLM\Agents\AgentExecutor\ExecutorInterceptorInterface;
use LLM\Agents\AgentExecutor\ExecutorInterface;

final readonly class InjectModelInterceptor implements ExecutorInterceptorInterface
{
    public function __construct(
        private AgentRepositoryInterface $agents,
    ) {}

    public function execute(
        ExecutionInput $input,
        ExecutorInterface $next,
    ): Execution {
        $agent = $this->agents->get($input->agent);

        $input = $input->withOptions(
            $input->options->with('model', $agent->getModel()->name),
        );

        return $next->execute(
            agent: $input->agent,
            prompt: $input->prompt,
            context: $input->context,
            options: $input->options,
            promptContext: $input->promptContext,
        );
    }
}
