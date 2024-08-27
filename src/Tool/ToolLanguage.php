<?php

declare(strict_types=1);

namespace LLM\Agents\Tool;

use LLM\Agents\Tool\Exception\LanguageIsNotSupportedException;

enum ToolLanguage: string
{
    public static function createFromString(string $language): ToolLanguage
    {
        return match ($language) {
            'application/x-httpd-php',
            'application/x-php',
            'php' => self::PHP,
            'application/x-lua',
            'lua' => self::Lua,
            default => throw new LanguageIsNotSupportedException($language),
        };
    }

    case PHP = 'php';

    case Lua = 'lua';
}
