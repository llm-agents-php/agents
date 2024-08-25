<?php

declare(strict_types=1);

namespace LLM\Agents\Tool;

use LLM\Agents\Tool\Exception\ToolNotFoundException;

interface ToolRepositoryInterface
{
    /**
     * @throws ToolNotFoundException
     */
    public function get(string $name): ToolInterface;

    public function has(string $name): bool;
}
