<?php

declare(strict_types=1);

namespace LLM\Agents\AgentExecutor\Interceptor;

use LLM\Agents\Agent\AgentRepositoryInterface;
use LLM\Agents\Agent\Execution;
use LLM\Agents\AgentExecutor\ExecutorInterceptorInterface;
use LLM\Agents\AgentExecutor\ExecutorInterface;
use LLM\Agents\LLM\AgentPromptGeneratorInterface;
use LLM\Agents\LLM\ContextInterface;
use LLM\Agents\LLM\OptionsInterface;
use LLM\Agents\LLM\Prompt\Chat\Prompt;
use LLM\Agents\LLM\PromptContextInterface;

final readonly class GeneratePromptInterceptor implements ExecutorInterceptorInterface
{
    public function __construct(
        private AgentRepositoryInterface $agents,
        private AgentPromptGeneratorInterface $promptGenerator,
    ) {}

    public function execute(
        string $agent,
        \Stringable|string|Prompt $prompt,
        ContextInterface $context,
        OptionsInterface $options,
        PromptContextInterface $promptContext,
        ExecutorInterface $next,
    ): Execution {
        if (!$prompt instanceof Prompt) {
            $prompt = $this->promptGenerator->generate(
                $this->agents->get($agent),
                $prompt,
                $promptContext,
            );
        }

        return $next->execute(
            agent: $agent,
            prompt: $prompt,
            context: $context,
            options: $options,
            promptContext: $promptContext,
        );
    }
}
