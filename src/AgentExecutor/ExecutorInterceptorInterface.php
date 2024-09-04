<?php

declare(strict_types=1);

namespace LLM\Agents\AgentExecutor;

use LLM\Agents\Agent\Execution;
use LLM\Agents\LLM\ContextInterface;
use LLM\Agents\LLM\OptionsInterface;
use LLM\Agents\LLM\Prompt\Chat\Prompt;
use LLM\Agents\LLM\PromptContextInterface;

interface ExecutorInterceptorInterface
{
    public function execute(
        string $agent,
        string|\Stringable|Prompt $prompt,
        ContextInterface $context,
        OptionsInterface $options,
        PromptContextInterface $promptContext,
        ExecutorInterface $next,
    ): Execution;
}
