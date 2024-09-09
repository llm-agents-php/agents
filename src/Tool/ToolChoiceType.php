<?php

declare(strict_types=1);

namespace LLM\Agents\Tool;

enum ToolChoiceType
{
    case Auto;
    case Any;
    case Specific;
    case None;
}
