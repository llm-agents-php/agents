<?php

declare(strict_types=1);

namespace LLM\Agents\LLM\Prompt;

interface StringPromptInterface extends PromptInterface, \Stringable
{
    public function format(array $variables = []): string;
}
