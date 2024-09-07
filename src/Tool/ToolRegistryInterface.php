<?php

declare(strict_types=1);

namespace LLM\Agents\Tool;

/**
 * This interface defines the contract for registering and retrieving tools.
 */
interface ToolRegistryInterface
{
    /**
     * Register one or more tools in the registry.
     */
    public function register(ToolInterface ...$tools): void;

    /**
     * Retrieve all registered tools.
     *
     * @return iterable<ToolInterface> An iterable collection of all registered tools
     */
    public function all(): iterable;
}
