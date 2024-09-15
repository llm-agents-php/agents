<?php

declare(strict_types=1);

namespace LLM\Agents\LLM\Output;

use LLM\Agents\LLM\Exception\FormatterException;
use LLM\Agents\LLM\Exception\InvalidArgumentException;

/**
 * This formatter is used to validate that the response is one of the provided options.
 */
final readonly class SelectFormatter implements FormatterInterface
{
    protected array $options;

    public function __construct(string ...$options)
    {
        if ($options === []) {
            throw new InvalidArgumentException('At least one option must be provided');
        }
        $this->options = \array_values($options);
    }

    public function format(string|\Stringable $output): string
    {
        $output = \trim($output);

        if (!\in_array($output, $this->options)) {
            throw new FormatterException(
                \sprintf(
                    "Response '%s' is not one of the expected values: %s",
                    $output,
                    \implode(', ', $this->options),
                ),
            );
        }

        return $output;
    }

    public function getInstruction(): ?string
    {
        return \sprintf(
            <<<'INSTRUCTION'
Your response must be one of the following options: %s.
Provide the option exactly as shown.
Don\'t include any additional information.
INSTRUCTION,
            \implode(', ', $this->options),
        );
    }
}
