<?php

declare(strict_types=1);

namespace LLM\Agents\Tool;

/**
 * @template T of object
 * @extends Tool<T>
 */
abstract class PhpTool extends Tool
{
    /**
     * Get the programming language used to implement the tool.
     */
    public function getLanguage(): ToolLanguage
    {
        return ToolLanguage::PHP;
    }

    /**
     * Execute the tool with the given input.
     *
     * @param T $input
     */
    abstract public function execute(object $input): string|\Stringable;
}
