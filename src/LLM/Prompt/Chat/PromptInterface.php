<?php

declare(strict_types=1);

namespace LLM\Agents\LLM\Prompt\Chat;

use LLM\Agents\LLM\Prompt\MessageInterface;
use LLM\Agents\LLM\Prompt\PromptInterface as ChatPromptInterface;

interface PromptInterface extends ChatPromptInterface, \Stringable
{
    /**
     * @return MessageInterface[]
     */
    public function format(array $variables = []): array;

    /** @return MessageInterface[] */
    public function getMessages(): array;
}