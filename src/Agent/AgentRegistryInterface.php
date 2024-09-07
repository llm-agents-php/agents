<?php

declare(strict_types=1);

namespace LLM\Agents\Agent;

use LLM\Agents\Agent\Exception\AgentAlreadyRegisteredException;

/**
 * It defines the contract for a registry that manages the registration of agents.
 *
 * Purpose:
 * - Provide a centralized mechanism for registering new agents in the system.
 * - Allow for dynamic addition of agents at runtime.
 */
interface AgentRegistryInterface
{
    /**
     * Register a new agent in the system.
     *
     * @throws AgentAlreadyRegisteredException
     */
    public function register(AgentInterface $agent): void;

    /**
     * Retrieve all registered agents.
     *
     * @return AgentInterface[]
     */
    public function all(): iterable;
}
