<?php

declare(strict_types=1);

namespace LLM\Agents\Agent;

use LLM\Agents\Solution\AgentLink;

interface HasLinkedAgentsInterface
{
    /**
     * Get the list of other agents this agent can interact with.
     *
     * @return array<AgentLink>
     */
    public function getAgents(): array;
}
