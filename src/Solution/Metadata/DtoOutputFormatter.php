<?php

declare(strict_types=1);

namespace LLM\Agents\Solution\Metadata;

/**
 * Instruct an agent to use a specific DTO class to format the output.
 * If the class is DTO it must implement \JsonSerializable.
 */
final readonly class DtoOutputFormatter extends Option
{
    /**
     * @param class-string $dtoClass DTO class or an Enum
     */
    public function __construct(string $dtoClass)
    {
        parent::__construct(
            key: 'output_formatter',
            content: $dtoClass,
        );
    }
}
