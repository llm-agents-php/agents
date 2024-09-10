<?php

declare(strict_types=1);

namespace LLM\Agents\AgentExecutor;

use LLM\Agents\LLM\ContextInterface;
use LLM\Agents\LLM\OptionsInterface;
use LLM\Agents\LLM\Prompt\PromptInterface;
use LLM\Agents\LLM\PromptContextInterface;

/**
 * Represents the immutable input for an agent execution.
 *
 * This class encapsulates all necessary information for executing an agent,
 * including the agent key, prompt, context, options, and prompt context.
 * It is designed to be immutable, with all modifier methods returning new instances.
 *
 * @example
 * // Creating an initial ExecutionInput
 * $input = new ExecutionInput(
 *     agent: 'my_agent',
 *     prompt: 'Hello, agent!',
 *     context: $context,
 *     options: $options,
 *     promptContext: $promptContext
 * );
 *
 * // Modifying the input (creates a new instance)
 * $newInput = $input->withAgent(...)
 *                   ->withPrompt(...;
 *
 * // The original $input remains unchanged
 * assert($input->agent === 'my_agent');
 * assert($newInput->agent === 'another_agent');
 */
final readonly class ExecutionInput
{
    public function __construct(
        public string $agent,
        public \Stringable|string|PromptInterface $prompt,
        public ContextInterface $context,
        public OptionsInterface $options,
        public PromptContextInterface $promptContext,
    ) {}

    /**
     * Create a new input with a different agent.
     */
    public function withAgent(string $agent): self
    {
        return new self(
            $agent,
            $this->prompt,
            $this->context,
            $this->options,
            $this->promptContext,
        );
    }

    /**
     * Create a new input with a different prompt context.
     */
    public function withPromptContext(PromptContextInterface $context): self
    {
        return new self(
            $this->agent,
            $this->prompt,
            $this->context,
            $this->options,
            $context,
        );
    }

    /**
     * Create a new input with a different context.
     */
    public function withContext(ContextInterface $context): self
    {
        return new self(
            $this->agent,
            $this->prompt,
            $context,
            $this->options,
            $this->promptContext,
        );
    }

    /**
     * Create a new input with a different prompt.
     */
    public function withPrompt(PromptInterface $prompt): self
    {
        return new self(
            $this->agent,
            $prompt,
            $this->context,
            $this->options,
            $this->promptContext,
        );
    }

    /**
     * Create a new input with different options.
     */
    public function withOptions(OptionsInterface $options): self
    {
        return new self(
            $this->agent,
            $this->prompt,
            $this->context,
            $options,
            $this->promptContext,
        );
    }
}
