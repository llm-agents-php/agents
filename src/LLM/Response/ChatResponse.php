<?php

declare(strict_types=1);

namespace LLM\Agents\LLM\Response;

use LLM\Agents\LLM\Prompt\Chat\ChatMessage;
use LLM\Agents\LLM\Prompt\Chat\Role;

class ChatResponse extends Response
{
    public function __construct(
        string|\Stringable|\JsonSerializable $content,
    ) {
        parent::__construct($content);
    }

    public function toMessage(): ChatMessage
    {
        return new ChatMessage(
            content: $this->content,
            role: Role::Assistant,
        );
    }
}