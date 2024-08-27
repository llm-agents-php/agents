<?php

declare(strict_types=1);

namespace LLM\Agents\LLM\Response;

use JsonSerializable;
use Stringable;

class StreamChatResponse extends ChatResponse
{
    public function __construct(
        string|Stringable|JsonSerializable $content,
        public readonly string $finishReason,
    ) {
        parent::__construct($content);
    }
}
