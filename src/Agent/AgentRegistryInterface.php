<?php

declare(strict_types=1);

namespace LLM\Agents\Agent;

use LLM\Agents\Agent\Exception\AgentAlreadyRegisteredException;

interface AgentRegistryInterface
{
    /**
     * @throws AgentAlreadyRegisteredException
     */
    public function register(AgentInterface $agent): void;

    /**
     * @return AgentInterface[]
     */
    public function all(): iterable;
}
