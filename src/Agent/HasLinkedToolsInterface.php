<?php

declare(strict_types=1);

namespace LLM\Agents\Agent;

use LLM\Agents\Solution\ToolLink;

interface HasLinkedToolsInterface
{
    /**
     * Get the list of tools available to the agent.
     *
     * @return array<ToolLink>
     */
    public function getTools(): array;
}
