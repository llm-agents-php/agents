<?php

declare(strict_types=1);

namespace LLM\Agents\Tool;

/**
 * Implemented by tools that require their code to be executed by a language-specific executor.
 */
interface LanguageExecutorAwareInterface
{
    /**
     * Get the code to be executed by the language executor.
     */
    public function getExecutableCode(): string;
}
