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

final readonly class InjectModelInterceptor implements ExecutorInterceptorInterface
{
    public function __construct(
        private AgentRepositoryInterface $agents,
    ) {}

    public function execute(
        string $agent,
        \Stringable|string|Prompt $prompt,
        ContextInterface $context,
        OptionsInterface $options,
        PromptContextInterface $promptContext,
        ExecutorInterface $next,
    ): Execution {
        $agent = $this->agents->get($agent);

        return $next->execute(
            agent: $agent->getKey(),
            prompt: $prompt,
            context: $context,
            options: $options->with('model', $agent->getModel()->name),
            promptContext: $promptContext,
        );
    }
}
