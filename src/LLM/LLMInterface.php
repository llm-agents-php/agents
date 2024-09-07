<?php

declare(strict_types=1);

namespace LLM\Agents\LLM;

use LLM\Agents\LLM\Prompt\PromptInterface;
use LLM\Agents\LLM\Response\Response;

/**
 * This interface defines the contract for LLM implementations.
 * It provides a standardized way to generate responses from various LLM providers.
 */
interface LLMInterface
{
    /**
     * Generate a response from the LLM based on the given prompt and context.
     *
     * This method is responsible for sending the prompt to the LLM, processing the response,
     * and returning it in a standardized format.
     *
     * @param ContextInterface $context The context for the current request, which may include user information, session data, or other relevant details.
     * @param PromptInterface $prompt The prompt to send to the LLM. This could be a simple string or a more complex structure containing multiple messages.
     * @param OptionsInterface $options Additional options to customize the LLM request, such as temperature, max tokens, or other model-specific parameters.
     *
     * @throws \LLM\Agents\LLM\Exception\LLMException If there's an error communicating with the LLM or processing the response.
     * @throws \LLM\Agents\LLM\Exception\RateLimitException If the LLM provider's rate limit is exceeded.
     * @throws \LLM\Agents\LLM\Exception\TimeoutException If the request to the LLM times out.
     * @throws \LLM\Agents\LLM\Exception\LimitExceededException If the request exceeds the LLM provider's limits (e.g., max tokens, max characters).
     */
    public function generate(
        ContextInterface $context,
        PromptInterface $prompt,
        OptionsInterface $options,
    ): Response;
}
