<?php

declare(strict_types=1);

namespace LLM\Agents\AgentExecutor;

use LLM\Agents\Agent\Execution;
use LLM\Agents\LLM\ContextInterface;
use LLM\Agents\LLM\OptionsInterface;
use LLM\Agents\LLM\Prompt\Chat\Prompt;
use LLM\Agents\LLM\Prompt\Context;
use LLM\Agents\LLM\PromptContextInterface;

/**
 * This interface defines the contract for agent executors.
 */
interface ExecutorInterface
{
    /**
     * Execute an agent task with the given parameters.
     *
     * @param string $agent The unique identifier or key of the agent to execute.
     * @param string|\Stringable|Prompt $prompt The prompt to send to the agent.
     * @param ContextInterface|null $context An optional execution context carrying user-specific information such as authentication details, session data, etc.
     * @param OptionsInterface|null $options Optional configuration options specific to the LLM being used. This includes settings like temperature, max tokens, etc.
     * @param PromptContextInterface $promptContext Additional context for prompt generation. This is used to provide extra information for generating the prompt.
     *
     * @throws \LLM\Agents\Agent\Exception\AgentNotFoundException If the specified agent is not found.
     * @throws \LLM\Agents\AgentExecutor\Exception\ExecutorException If an error occurs during execution.
     */
    public function execute(
        string $agent,
        string|\Stringable|Prompt $prompt,
        ?ContextInterface $context = null,
        ?OptionsInterface $options = null,
        PromptContextInterface $promptContext = new Context(),
    ): Execution;

    /**
     * Add one or more interceptors to the executor's pipeline.
     *
     * Interceptors allow for modifying the execution flow, adding pre- or post-processing steps,
     * or altering the behavior of the executor without changing its core implementation.
     * This can be used for tasks such as logging, modifying the context or options, or
     * implementing complex execution strategies.
     */
    public function withInterceptor(ExecutorInterceptorInterface ...$interceptor): self;
}
