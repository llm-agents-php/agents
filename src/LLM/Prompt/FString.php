<?php

declare(strict_types=1);

namespace LLM\Agents\LLM\Prompt;

/**
 * Formater ("f-string") must implement https://peps.python.org/pep-3101/
 *
 * @see langchain-php
 */
final class FString implements FormatterInterface
{
    public function format(string $string, array $values = []): string
    {
        $transformed = [];
        foreach ($values as $key => $value) {
            $transformed['{' . $key . '}'] = $value;
        }

        return \strtr($string, $transformed);
    }

    public function validate(string $string, array $values = []): bool
    {
        if ($string === '') {
            return false;
        }

        $parsed = $this->parse($string);

        if (\count($parsed) !== \count($values)) {
            return false;
        }

        return \array_diff($parsed, \array_flip($values)) === [];
    }

    public function parse(string $string): array
    {
        $matches = [];
        \preg_match_all('/\{[a-zA-Z0-9_]+\}/', $string, $matches);

        $variables = [];
        foreach ($matches[0] as $match) {
            $variables[] = \trim($match, '{}');
        }

        return \array_unique($variables);
    }

    public static function f(string $string, array $values = []): string
    {
        $transformed = [];
        foreach ($values as $key => $value) {
            $transformed['{' . $key . '}'] = $value;
        }

        return \strtr($string, $transformed);
    }
}
