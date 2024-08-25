<?php

declare(strict_types=1);

namespace LLM\Agents\LLM;

use LLM\Agents\LLM\Prompt\PromptInterface;
use LLM\Agents\LLM\Response\Response;

interface LLMInterface
{
    public function generate(
        ContextInterface $context,
        PromptInterface $prompt,
        OptionsInterface $options,
    ): Response;
}