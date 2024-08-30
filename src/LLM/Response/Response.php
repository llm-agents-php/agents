<?php

declare(strict_types=1);

namespace LLM\Agents\LLM\Response;

use JsonSerializable;
use Stringable;

class Response
{
    public function __construct(
        public readonly string|Stringable|JsonSerializable $content,
    ) {
    }
}
