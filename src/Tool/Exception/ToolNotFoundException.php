<?php

declare(strict_types=1);

namespace LLM\Agents\Tool\Exception;

use function sprintf;

final class ToolNotFoundException extends ToolException
{
    public function __construct(string $tool)
    {
        parent::__construct(sprintf('Tool not found: %s', $tool));
    }
}
