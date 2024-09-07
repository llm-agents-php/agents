<?php

declare(strict_types=1);

namespace LLM\Agents\Agent;

namespace LLM\Agents\Agent;

/**
 * It defines a contract for creating instances of agents. It encapsulates the complex process of agent
 * initialization, configuration, and setup.
 *
 * Purpose:
 * 1. Abstraction: It provides a level of abstraction between the agent creation
 *    process and the rest of the application, allowing for different implementation
 *    strategies without affecting the client code.
 *
 * 2. Flexibility: By using a factory interface, the framework can support multiple
 *    types of agents or different initialization strategies for agents without
 *    changing the core logic.
 */
interface AgentFactoryInterface
{
    /**
     * Create and return a fully initialized agent instance.
     */
    public function create(): AgentInterface;
}
