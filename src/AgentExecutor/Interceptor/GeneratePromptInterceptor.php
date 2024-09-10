<?php

declare(strict_types=1);

namespace LLM\Agents\AgentExecutor\Interceptor;

use LLM\Agents\Agent\AgentRepositoryInterface;
use LLM\Agents\Agent\Execution;
use LLM\Agents\AgentExecutor\ExecutionInput;
use LLM\Agents\AgentExecutor\ExecutorInterceptorInterface;
use LLM\Agents\AgentExecutor\InterceptorHandler;
use LLM\Agents\LLM\AgentPromptGeneratorInterface;
use LLM\Agents\LLM\Prompt\PromptInterface;

/**
 * This interceptor is responsible for generating the prompt for the agent.
 */
final readonly class GeneratePromptInterceptor implements ExecutorInterceptorInterface
{
    public function __construct(
        private AgentRepositoryInterface $agents,
        private AgentPromptGeneratorInterface $promptGenerator,
    ) {}

    public function execute(
        ExecutionInput $input,
        InterceptorHandler $next,
    ): Execution {
        if (!$input->prompt instanceof PromptInterface) {
            $input = $input->withPrompt(
                $this->promptGenerator->generate(
                    $this->agents->get($input->agent),
                    $input->prompt,
                    $input->promptContext,
                ),
            );
        }

        $execution = $next($input);

        // Remove temporary messages from the prompt.
        $prompt = $execution->prompt;
        if ($prompt instanceof \LLM\Agents\LLM\Prompt\Chat\PromptInterface) {
            $prompt = $prompt->withoutTempMessages();
        }

        return new Execution(
            result: $execution->result,
            prompt: $prompt,
        );
    }
}
