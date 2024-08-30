<?php

declare(strict_types=1);

namespace LLM\Agents\Tool\Exception;

final class UnsupportedToolExecutionException extends ToolException
{
    public function __construct(string $tool)
    {
        parent::__construct(\sprintf('Tool does not support execution: %s', $tool));
    }
}
