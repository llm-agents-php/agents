<?php

declare(strict_types=1);

namespace LLM\Agents\AgentExecutor;

use LLM\Agents\LLM\ContextInterface;
use LLM\Agents\LLM\OptionsInterface;
use LLM\Agents\LLM\Prompt\Chat\PromptInterface;
use LLM\Agents\LLM\PromptContextInterface;

final readonly class ExecutionInput
{
    public function __construct(
        public string $agent,
        public \Stringable|string|PromptInterface $prompt,
        public ContextInterface $context,
        public OptionsInterface $options,
        public PromptContextInterface $promptContext,
    ) {}

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
