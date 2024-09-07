<?php

declare(strict_types=1);

namespace LLM\Agents\Agent;

use LLM\Agents\LLM\Prompt\Chat\PromptInterface;
use LLM\Agents\LLM\Response\Response;

/**
 * Represents the result of an agent's execution, including the response and the prompt used.
 */
final readonly class Execution
{
    /**
     * @param Response $result The response from the agent's execution.
     * @param PromptInterface $prompt The prompt used for the execution.
     */
    public function __construct(
        public Response $result,
        public PromptInterface $prompt,
    ) {}
}
