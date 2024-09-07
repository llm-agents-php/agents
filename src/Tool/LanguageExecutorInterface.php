<?php

declare(strict_types=1);

namespace LLM\Agents\Tool;

/**
 * Defines the contract for language-specific executors that can run tool code.
 *
 * @example
 * Here's an example implementation for a Python language executor:
 *
 * ```php
 * class PythonLanguageExecutor implements LanguageExecutorInterface
 * {
 *     public function execute(ToolInterface $tool, object $input): string|\Stringable
 *     {
 *         if (!$tool instanceof LanguageExecutorAwareInterface) {
 *             throw new \InvalidArgumentException("Tool must implement LanguageExecutorAwareInterface for Python execution.");
 *         }
 *
 *         $code = $tool->getExecutableCode();
 *
 *         // Execute the Python script
 *         $output = ....; // Run the script using the Python interpreter
 *
 *         return $output;
 *     }
 * }
 * ```
 */
interface LanguageExecutorInterface
{
    /**
     * Execute the given tool with the provided input.
     *
     * This method should handle the execution of the tool's code in the specific
     * language that this executor is designed for. It should properly set up the
     * execution environment, run the code, and handle any language-specific
     * intricacies.
     *
     * @param ToolInterface $tool The tool to be executed. For non-PHP languages, this should typically implement LanguageExecutorAwareInterface.
     */
    public function execute(ToolInterface $tool, object $input): string|\Stringable;
}
