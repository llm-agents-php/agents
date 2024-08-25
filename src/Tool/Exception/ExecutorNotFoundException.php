<?php

declare(strict_types=1);

namespace LLM\Agents\Tool\Exception;

use LLM\Agents\Tool\ToolLanguage;

final class ExecutorNotFoundException extends ToolException
{
    public function __construct(ToolLanguage $language)
    {
        parent::__construct(sprintf('Executor not found for language: %s', $language->value));
    }
}
