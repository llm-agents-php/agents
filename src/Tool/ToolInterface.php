<?php

declare(strict_types=1);

namespace LLM\Agents\Tool;

/**
 * Represents a tool that can be used by an AI agent to perform specific tasks.
 *
 * @template T of object
 */
interface ToolInterface
{
    /**
     * Get the unique name of the tool.
     */
    public function getName(): string;

    /**
     * Get the human-readable description of the tool's functionality.
     */
    public function getDescription(): string;

    /**
     * Get the input schema class name for the tool.
     *
     * @return class-string<T>|string
     */
    public function getInputSchema(): string;

    /**
     * Get the programming language used to implement the tool.
     */
    public function getLanguage(): ToolLanguage;
}
