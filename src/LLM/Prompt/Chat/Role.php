<?php

declare(strict_types=1);

namespace LLM\Agents\LLM\Prompt\Chat;

enum Role: string
{
    case System = 'system';
    case User = 'user';
    case Assistant = 'assistant';
    case Tool = 'tool';

    public static function fromValue(string $value): self
    {
        return match ($value) {
            'system' => self::System,
            'user' => self::User,
            'assistant' => self::Assistant,
            'tool' => self::Tool,
            default => throw new \InvalidArgumentException("Invalid role value: $value"),
        };
    }
}
