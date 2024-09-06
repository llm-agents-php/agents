<?php

declare(strict_types=1);

namespace LLM\Agents\Agent;

use LLM\Agents\Solution\AgentLink;

interface HasLinkedAgentsInterface
{
    /**
     * @return array<AgentLink>
     */
    public function getAgents(): array;
}
