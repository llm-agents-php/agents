<?php

declare(strict_types=1);

namespace LLM\Agents\LLM\Output;

use LLM\Agents\LLM\Exception\FormatterException;
use LLM\Agents\LLM\Exception\InvalidArgumentException;

/**
 * This formatter is used to validate that the response is one of the provided options.
 */
final readonly class EnumFormatter implements FormatterInterface
{
    private \ReflectionEnum $enum;
    private FormatterInterface $formatter;

    /**
     * @param class-string $enumClass
     */
    public function __construct(
        string $enumClass,
    ) {
        // validate class is type of enum of strings and fetch options
        try {
            $this->enum = new \ReflectionEnum($enumClass);
        } catch (\ReflectionException $e) {
            throw new InvalidArgumentException("Class {$enumClass} is not a valid enum");
        }

        if (!$this->enum->getBackingType() instanceof \ReflectionNamedType) {
            throw new InvalidArgumentException("Enum {$enumClass} is not a valid enum of strings");
        }

        if ($this->enum->getBackingType()->getName() !== 'string') {
            throw new InvalidArgumentException("Enum {$enumClass} is not a valid enum of strings");
        }

        // fetching options
        $this->formatter = new SelectFormatter(
            ...\array_map(static fn($option) => $option->value, $this->enum->getConstants()),
        );
    }

    public function format(string|\Stringable $output): mixed
    {
        $value = $this->formatter->format($output);

        // new enum value
        foreach ($this->enum->getConstants() as $option) {
            if ($option->value === $value) {
                return $option;
            }
        }

        throw new FormatterException("Invalid enum value {$value}");
    }

    public function getInstruction(): ?string
    {
        return $this->formatter->getInstruction();
    }
}
