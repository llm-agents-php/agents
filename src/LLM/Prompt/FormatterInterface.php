<?php

declare(strict_types=1);

namespace LLM\Agents\LLM\Prompt;

interface FormatterInterface
{
    public function format(string $string, array $values = []): string;
}
