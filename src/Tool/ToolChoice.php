<?php

declare(strict_types=1);

namespace LLM\Agents\Tool;

readonly class ToolChoice
{
    private function __construct(
        public ToolChoiceType $type,
        public ?string $toolName = null,
    ) {}

    public static function auto(): self
    {
        return new self(ToolChoiceType::Auto);
    }

    public static function any(): self
    {
        return new self(ToolChoiceType::Any);
    }

    public static function specific(string $toolName): self
    {
        return new self(ToolChoiceType::Specific, $toolName);
    }

    public static function none(): self
    {
        return new self(ToolChoiceType::None);
    }

    public function isAuto(): bool
    {
        return $this->type === ToolChoiceType::Auto;
    }

    public function isAny(): bool
    {
        return $this->type === ToolChoiceType::Any;
    }

    public function isSpecific(): bool
    {
        return $this->type === ToolChoiceType::Specific;
    }

    public function isNone(): bool
    {
        return $this->type === ToolChoiceType::None;
    }
}
