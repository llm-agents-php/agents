<?php

declare(strict_types=1);

namespace LLM\Agents\Tool;

use LLM\Agents\Solution\ToolLink;

abstract class Tool extends ToolLink implements ToolInterface
{
    public function __construct(
        string $name,
        /** @var class-string */
        public readonly string $inputSchema,
        string $description,
    ) {
        parent::__construct(
            name: $name,
            description: $description,
        );
    }

    public function getDescription(): string
    {
        return $this->description ?? '';
    }

    public function getInputSchema(): string
    {
        return $this->inputSchema;
    }
}
