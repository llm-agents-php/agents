<?php

declare(strict_types=1);

namespace LLM\Agents\Tool;

interface ToolRegistryInterface
{
    public function register(ToolInterface ...$tools): void;

    /**
     * @return iterable<ToolInterface>
     */
    public function all(): iterable;
}
