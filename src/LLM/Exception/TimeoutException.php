<?php

declare(strict_types=1);

namespace LLM\Agents\LLM\Exception;

final class TimeoutException extends LLMException
{
    public function __construct(
        string $message = "Request timed out",
        int $code = 0,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
