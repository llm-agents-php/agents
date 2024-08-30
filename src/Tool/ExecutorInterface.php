<?php

declare(strict_types=1);

namespace LLM\Agents\Tool;

interface ExecutorInterface
{
    public function execute(string $code, object $input): string|\Stringable;
}
