<?php

declare(strict_types=1);

namespace LLM\Agents\LLM\Response;

use LLM\Agents\LLM\Prompt\Chat\ToolCalledPrompt;

final class ToolCalledResponse extends Response
{
    /**
     * @param array<ToolCall> $tools
     */
    public function __construct(
        string|\Stringable|\JsonSerializable $content,
        public readonly array $tools,
        public readonly string $finishReason,
    ) {
        parent::__construct($content);
    }

    public function toMessage(): ToolCalledPrompt
    {
        return new ToolCalledPrompt(
            tools: $this->tools,
        );
    }
}