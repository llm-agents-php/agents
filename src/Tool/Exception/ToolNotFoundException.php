<?php

declare(strict_types=1);

namespace LLM\Agents\Tool\Exception;

final class ToolNotFoundException extends ToolException
{
    public function __construct(string $tool)
    {
        parent::__construct(\sprintf('Tool not found: %s', $tool));
    }
}
