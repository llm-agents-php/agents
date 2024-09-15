<?php

declare(strict_types=1);

namespace LLM\Agents\LLM\Prompt;

/**
 * @internal
 */
readonly class Tool
{
    public function __construct(
        public string $name,
        public string $description,
        public array $parameters = [],
        public bool $enabled = true,
        public bool $strict = true,
        public bool $additionalProperties = false,
    ) {}
}
