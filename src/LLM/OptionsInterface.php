<?php

declare(strict_types=1);

namespace LLM\Agents\LLM;

/**
 * Provides a flexible and standardized way to manage configuration options for LLM client.
 *
 *  Example usage:
 *  ```php
 *  // Create a new instance of Options
 *  $options = new Options();
 *
 *  // Set common LLM options
 *  $options = $options
 *      ->with('model', 'gpt-4')
 *      ->with('max_tokens', 150)
 *      ->with('temperature', 0.7)
 *      ->with('top_p', 1.0)
 *      ->with('frequency_penalty', 0.0)
 *      ->with('presence_penalty', 0.0);
 *  ```
 * An object will be passed to the LLMInterface where it can be used to configure the LLM client.
 */
interface OptionsInterface extends \IteratorAggregate
{
    /**
     * Check if a specific option exists.
     */
    public function has(string $option): bool;

    /**
     * Get the value of a specific option.
     */
    public function get(string $option, mixed $default = null): mixed;

    /**
     * Create a new instance with an additional or modified option.
     * This method should not modify the current instance, but return a new one.
     */
    public function with(string $option, mixed $value): static;

    /**
     * Merge the current options with another set of options.
     * This method should not modify the current instance, but return a new one.
     */
    public function merge(OptionsInterface $options): static;
}
