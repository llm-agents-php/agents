<?php

declare(strict_types=1);

namespace LLM\Agents\LLM\Prompt\Chat;

use InvalidArgumentException;

enum Role: string
{
    public static function fromValue(string $value): self
    {
        return match ($value) {
            'system' => self::System,
            'user' => self::User,
            'assistant' => self::Assistant,
            'tool' => self::Tool,
            default => throw new InvalidArgumentException("Invalid role value: $value"),
        };
    }

    case System = 'system';

    case User = 'user';

    case Assistant = 'assistant';

    case Tool = 'tool';
}
