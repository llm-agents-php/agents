<?php

declare(strict_types=1);

namespace LLM\Agents\LLM;

use LLM\Agents\Agent\AgentInterface;
use LLM\Agents\LLM\Prompt\Chat\Prompt;
use LLM\Agents\LLM\Prompt\Chat\PromptInterface;

/**
 * Interface for generating prompts for AI agents.
 *
 * This interface defines the contract for classes responsible for generating
 * prompts that will be sent to AI agents. It allows for customization of the
 * prompt based on the specific agent, user input, and context.
 */
interface AgentPromptGeneratorInterface
{
    /**
     * Generate a prompt for an AI agent.
     *
     * @param AgentInterface $agent The agent for which to generate the prompt.
     * @param string|\Stringable $userPrompt The user's input or query.
     * @param PromptContextInterface $context Additional context for prompt generation.
     * @param PromptInterface $prompt An optional initial prompt to build upon.
     *
     * @return PromptInterface The generated prompt ready to be sent to the AI agent.
     */
    public function generate(
        AgentInterface $agent,
        string|\Stringable $userPrompt,
        PromptContextInterface $context,
        PromptInterface $prompt = new Prompt(),
    ): PromptInterface;
}
