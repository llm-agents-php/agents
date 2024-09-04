<?php

declare(strict_types=1);

namespace LLM\Agents\LLM;

use LLM\Agents\Agent\AgentInterface;
use LLM\Agents\LLM\Prompt\Chat\PromptInterface;

interface AgentPromptGeneratorInterface
{
    public function generate(
        AgentInterface $agent,
        string|\Stringable $prompt,
        PromptContextInterface $context,
    ): PromptInterface;
}
