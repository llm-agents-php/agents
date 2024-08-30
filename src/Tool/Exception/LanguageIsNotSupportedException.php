<?php

declare(strict_types=1);

namespace LLM\Agents\Tool\Exception;

final class LanguageIsNotSupportedException extends ToolException
{
    public function __construct(string $language)
    {
        parent::__construct(\sprintf('Language "%s" is not supported.', $language));
    }
}
