<?php

declare(strict_types=1);

namespace LLM\Agents\AgentExecutor\Interceptor;

use LLM\Agents\Agent\AgentRepositoryInterface;
use LLM\Agents\Agent\Execution;
use LLM\Agents\AgentExecutor\ExecutionInput;
use LLM\Agents\AgentExecutor\ExecutorInterceptorInterface;
use LLM\Agents\AgentExecutor\InterceptorHandler;

/**
 * This interceptor is responsible for injecting the model name into the execution options.
 */
final readonly class InjectModelInterceptor implements ExecutorInterceptorInterface
{
    public function __construct(
        private AgentRepositoryInterface $agents,
    ) {}

    public function execute(
        ExecutionInput $input,
        InterceptorHandler $next,
    ): Execution {
        $agent = $this->agents->get($input->agent);

        $input = $input->withOptions(
            $input->options->with('model', $agent->getModel()->name),
        );

        return $next($input);
    }
}
