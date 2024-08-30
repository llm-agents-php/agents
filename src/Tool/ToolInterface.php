<?php

declare(strict_types=1);

namespace LLM\Agents\Tool;

/**
 * @template T of object
 */
interface ToolInterface
{
    public function getName(): string;

    public function getDescription(): string;

    /**
     * @return class-string<T>|string
     */
    public function getInputSchema(): string;

    public function getLanguage(): ToolLanguage;

    /**
     * @param T $input
     */
    public function execute(object $input): string|\Stringable;
}
