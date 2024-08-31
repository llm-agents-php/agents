<?php

declare(strict_types=1);

namespace LLM\Agents\Agent;

use LLM\Agents\Agent\Exception\AgentNotFoundException;

interface AgentRepositoryInterface
{
    /**
     * @throws AgentNotFoundException
     */
    public function get(string $key): AgentInterface;

    public function has(string $key): bool;
}
