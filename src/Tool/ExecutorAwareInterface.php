<?php

declare(strict_types=1);

namespace LLM\Agents\Tool;

interface ExecutorAwareInterface
{
    public function setExecutor(ExecutorInterface $executor): ToolInterface;
}
