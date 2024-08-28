<?php

declare(strict_types=1);

namespace LLM\Agents\Tool;

use LLM\Agents\Tool\Exception\LanguageIsNotSupportedException;

enum ToolLanguage: string
{
    case PHP = 'php';
    case Lua = 'lua';
    case Python = 'python';
    case Ruby = 'ruby';
    case JavaScript = 'javascript';
    case TypeScript = 'typescript';
    case Shell = 'shell';

    public static function createFromString(string $language): ToolLanguage
    {
        return match ($language) {
            // PHP
            'application/x-httpd-php',
            'application/x-php',
            'php' => self::PHP,

            // Lua
            'application/x-lua',
            'lua' => self::Lua,

            // Python
            'application/x-python',
            'python' => self::Python,

            // Ruby
            'application/x-ruby',
            'ruby' => self::Ruby,

            // JavaScript
            'application/javascript',
            'application/x-javascript',
            'text/javascript',
            'text/x-javascript',
            'text/x-js',
            'text/ecmascript',
            'application/ecmascript',
            'application/x-ecmascript',
            'javascript' => self::JavaScript,

            // TypeScript
            'application/typescript',
            'application/x-typescript',
            'text/typescript',
            'text/x-typescript',
            'typescript' => self::TypeScript,

            // Shell
            'application/x-sh',
            'application/x-shellscript',
            'text/x-sh',
            'text/x-shellscript',
            'shell' => self::Shell,
            default => throw new LanguageIsNotSupportedException($language),
        };
    }
}
