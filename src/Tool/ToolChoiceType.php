<?php

declare(strict_types=1);

namespace LLM\Agents\Tool;

enum ToolChoiceType
{
    // Let LLM decide which function to call or not to call at all
    case Auto;

    // Force LLM to always call one or more functions
    case Any;

    // Force LLM to call a specific function with the given name
    case Specific;

    // Force LLM not to call any function
    case None;
}
