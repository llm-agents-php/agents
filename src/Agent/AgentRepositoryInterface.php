<?php

declare(strict_types=1);

namespace LLM\Agents\Agent;

use LLM\Agents\Agent\Exception\AgentNotFoundException;

/**
 * It defines the contract for a repository that provides access to registered agents.
 *
 * Purpose:
 * - Provide a standardized way to retrieve specific agents by their unique keys.
 * - Allow checking for the existence of agents in the system.
 *
 * This interface is essential for components that need to work with specific
 * agents. It abstracts the storage and retrieval mechanisms, allowing for
 * different implementations (e.g., in-memory, database-backed) without
 * affecting the consumers of the interface.
 */
interface AgentRepositoryInterface
{
    /**
     * Retrieve an agent by its unique key.
     *
     * @param non-empty-string $key The unique key of the agent to retrieve.
     * @throws AgentNotFoundException
     */
    public function get(string $key): AgentInterface;

    /**
     * Check if an agent with the given key exists in the repository.
     *
     * @param non-empty-string $key The key to check for existence.
     */
    public function has(string $key): bool;

    /**
     * @return array<AgentInterface>
     */
    public function findByCapabilityKey(string $capabilityKey): iterable;
}
