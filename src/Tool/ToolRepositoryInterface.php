<?php

declare(strict_types=1);

namespace LLM\Agents\Tool;

use LLM\Agents\Tool\Exception\ToolNotFoundException;

/**
 * This interface defines the contract for retrieving specific tools from the repository.
 */
interface ToolRepositoryInterface
{
    /**
     * Retrieve a tool by its name.
     *
     * @param string $name The unique name of the tool to retrieve
     * @throws ToolNotFoundException If the tool with the given name is not found
     */
    public function get(string $name): ToolInterface;

    /**
     * Check if a tool with the given name exists in the repository.
     */
    public function has(string $name): bool;
}
