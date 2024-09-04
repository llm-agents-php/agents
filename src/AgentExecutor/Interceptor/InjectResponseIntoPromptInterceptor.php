<?php

declare(strict_types=1);

namespace LLM\Agents\AgentExecutor\Interceptor;

use LLM\Agents\Agent\Execution;
use LLM\Agents\AgentExecutor\ExecutionInput;
use LLM\Agents\AgentExecutor\ExecutorInterceptorInterface;
use LLM\Agents\AgentExecutor\InterceptorHandler;
use LLM\Agents\LLM\Response\ChatResponse;
use LLM\Agents\LLM\Response\ToolCalledResponse;

final class InjectResponseIntoPromptInterceptor implements ExecutorInterceptorInterface
{
    public function execute(
        ExecutionInput $input,
        InterceptorHandler $next,
    ): Execution {
        $execution = $next($input);

        $prompt = $execution->prompt;

        if (
            $execution->result instanceof ChatResponse
            || $execution->result instanceof ToolCalledResponse
        ) {
            $prompt = $prompt->withAddedMessage($execution->result->toMessage());
        }

        return new Execution(
            result: $execution->result,
            prompt: $prompt,
        );
    }
}
