<?php

declare(strict_types=1);

namespace LLM\Agents\AgentExecutor\Interceptor;

use LLM\Agents\Agent\AgentRepositoryInterface;
use LLM\Agents\Agent\Execution;
use LLM\Agents\AgentExecutor\ExecutionInput;
use LLM\Agents\AgentExecutor\ExecutorInterceptorInterface;
use LLM\Agents\AgentExecutor\ExecutorInterface;
use LLM\Agents\LLM\AgentPromptGeneratorInterface;
use LLM\Agents\LLM\Prompt\Chat\Prompt;

final readonly class GeneratePromptInterceptor implements ExecutorInterceptorInterface
{
    public function __construct(
        private AgentRepositoryInterface $agents,
        private AgentPromptGeneratorInterface $promptGenerator,
    ) {}

    public function execute(
        ExecutionInput $input,
        ExecutorInterface $next,
    ): Execution {
        if (!$input->prompt instanceof Prompt) {
            $input = $input->withPrompt(
                $this->promptGenerator->generate(
                    $this->agents->get($input->agent),
                    $input->prompt,
                    $input->promptContext,
                ),
            );
        }

        return $next->execute(
            agent: $input->agent,
            prompt: $input->prompt,
            context: $input->context,
            options: $input->options,
            promptContext: $input->promptContext,
        );
    }
}
