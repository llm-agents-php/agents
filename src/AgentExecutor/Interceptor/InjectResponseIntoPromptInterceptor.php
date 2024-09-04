<?php

declare(strict_types=1);

namespace LLM\Agents\AgentExecutor\Interceptor;

use LLM\Agents\Agent\Execution;
use LLM\Agents\AgentExecutor\ExecutorInterceptorInterface;
use LLM\Agents\AgentExecutor\ExecutorInterface;
use LLM\Agents\LLM\ContextInterface;
use LLM\Agents\LLM\OptionsInterface;
use LLM\Agents\LLM\Prompt\Chat\Prompt;
use LLM\Agents\LLM\PromptContextInterface;
use LLM\Agents\LLM\Response\ChatResponse;
use LLM\Agents\LLM\Response\ToolCalledResponse;

final class InjectResponseIntoPromptInterceptor implements ExecutorInterceptorInterface
{
    public function execute(
        string $agent,
        \Stringable|string|Prompt $prompt,
        ContextInterface $context,
        OptionsInterface $options,
        PromptContextInterface $promptContext,
        ExecutorInterface $next,
    ): Execution {
        $execution = $next->execute(
            agent: $agent,
            prompt: $prompt,
            context: $context,
            options: $options,
            promptContext: $promptContext,
        );

        if (
            $execution->result instanceof ChatResponse
            || $execution->result instanceof ToolCalledResponse
        ) {
            $prompt = $execution->prompt->withAddedMessage($execution->result->toMessage());
        }

        return new Execution(
            result: $execution->result,
            prompt: $prompt,
        );
    }
}
