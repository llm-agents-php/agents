<?php

declare(strict_types=1);

namespace LLM\Agents\Agent;

use LLM\Agents\Agent\Exception\AgentAlreadyRegisteredException;
use LLM\Agents\Agent\Exception\AgentNotFoundException;

final class AgentRegistry implements AgentRegistryInterface, AgentRepositoryInterface
{
    /** @var array<string, AgentInterface> */
    private array $agents = [];

    public function register(AgentInterface $agent): void
    {
        $key = $agent->getKey();

        if ($this->has($key)) {
            throw new AgentAlreadyRegisteredException(\sprintf('Agent with key [%s] is already registered.', $key));
        }

        $this->agents[$key] = $agent;
    }

    public function get(string $key): AgentInterface
    {
        if (!$this->has($key)) {
            throw new AgentNotFoundException(\sprintf('Agent with key \'%s\' is not registered.', $key));
        }

        return $this->agents[$key];
    }

    public function has(string $key): bool
    {
        return isset($this->agents[$key]);
    }

    public function all(): iterable
    {
        return $this->agents;
    }
}
