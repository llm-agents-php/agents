<?php

declare(strict_types=1);

namespace LLM\Agents\AgentExecutor\Interceptor;

use LLM\Agents\Agent\Execution;
use LLM\Agents\AgentExecutor\ExecutionInput;
use LLM\Agents\AgentExecutor\ExecutorInterceptorInterface;
use LLM\Agents\AgentExecutor\InterceptorHandler;
use LLM\Agents\LLM\Exception\LimitExceededException;

final readonly class TokenLimitRetryInterceptor implements ExecutorInterceptorInterface
{
    public function __construct(
        private int $maxRetries = 3,
        private int $incrementStep = 500,
        private string $limitKey = 'max_tokens',
    ) {}

    public function execute(
        ExecutionInput $input,
        InterceptorHandler $next,
    ): Execution {
        $retries = 0;

        while (true) {
            try {
                return $next($input);
            } catch (LimitExceededException $e) {
                if (++$retries > $this->maxRetries) {
                    throw $e;
                }

                $newLimit = $e->currentLimit + $this->incrementStep;
                $input = $input->withOptions(
                    $input->options->with($this->limitKey, $newLimit),
                );
            }
        }
    }
}
