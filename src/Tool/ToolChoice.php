<?php

declare(strict_types=1);

namespace LLM\Agents\Tool;

/**
 * In some cases, you may want LLM to use a specific tool to answer the user’s question, even if LLM thinks it can
 * provide an answer without using a tool.
 *
 * This class allows you to instruct LLM to have a specific behavior when it comes to using tools.
 */
readonly class ToolChoice
{
    private function __construct(
        public ToolChoiceType $type,
        public ?string $toolName = null,
    ) {}

    /**
     * Let LLM decide which function to call or not to call at all
     */
    public static function auto(): self
    {
        return new self(ToolChoiceType::Auto);
    }

    /**
     * Force LLM to always call one or more functions
     */
    public static function any(): self
    {
        return new self(ToolChoiceType::Any);
    }

    /**
     * Force LLM to call a specific function with the given name
     */
    public static function specific(string $toolName): self
    {
        return new self(ToolChoiceType::Specific, $toolName);
    }

    /**
     * Force LLM not to call any function
     */
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
