<?php

declare(strict_types=1);

namespace LLM\Agents\Solution\Metadata;

/**
 * Instruct an agent to return the tool result immediately after call by the LLM.
 */
final readonly class ReturnToolResult extends Option
{
    public function __construct()
    {
        parent::__construct(key: 'return_tool_result', content: true);
    }
}
