<?php

declare(strict_types=1);

namespace LLM\Agents\LLM\Output;

/**
 * This abstraction is responsible for instructing LLM to always respond in a specific format.
 */
interface FormatterInterface
{
    /**
     * Formats the output to the desired format.
     */
    public function format(string|\Stringable $output): mixed;

    /**
     * Returns instructions to be added to the prompt template to instruct LLM to always respond in a specific format.
     */
    public function getInstruction(): ?string;
}
