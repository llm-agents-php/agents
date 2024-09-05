<?php

declare(strict_types=1);

namespace LLM\Agents\LLM\Exception;

final class LimitExceededException extends LLMException
{
    public function __construct(
        public readonly int $currentLimit,
    ) {
        parent::__construct(
            \sprintf(
                'Tokens limit exceeded: %d',
                $currentLimit,
            ),
        );
    }
}
