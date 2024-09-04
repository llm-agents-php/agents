<?php

declare(strict_types=1);

namespace LLM\Agents\LLM;

use LLM\Agents\Agent\AgentInterface;
use LLM\Agents\LLM\Prompt\Chat\Prompt;
use LLM\Agents\LLM\Prompt\Chat\PromptInterface;

interface AgentPromptGeneratorInterface
{
    public function generate(
        AgentInterface $agent,
        string|\Stringable $userPrompt,
        PromptContextInterface $context,
        PromptInterface $prompt = new Prompt(),
    ): PromptInterface;
}
