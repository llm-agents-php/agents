<?php

declare(strict_types=1);

namespace LLM\Agents\LLM\Prompt;

interface PromptInterface extends MessageInterface
{
    /**
     * Creates new prompt with altered values.
     */
    public function withValues(array $values): self;
}