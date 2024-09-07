<?php

declare(strict_types=1);

namespace LLM\Agents\Tool\LanguageExecutor;

use LLM\Agents\Tool\LanguageExecutorInterface;
use LLM\Agents\Tool\ToolInterface;

/**
 * Executes PHP code.
 */
final readonly class PhpLanguageExecutor implements LanguageExecutorInterface
{
    public function execute(ToolInterface $tool, object $input): string|\Stringable
    {
        return $tool->execute($input);
    }
}
