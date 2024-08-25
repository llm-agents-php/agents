<?php

declare(strict_types=1);

namespace LLM\Agents\LLM\Response;

enum FinishReason: string
{
    case Stop = 'stop';
    case ToolCalls = 'tool_calls';
    case Limit = 'limit';
    case Timeout = 'timeout';
    case Length = 'length';
}
