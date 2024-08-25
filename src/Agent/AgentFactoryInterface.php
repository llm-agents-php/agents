<?php

declare(strict_types=1);

namespace LLM\Agents\Agent;

interface AgentFactoryInterface
{
    public function create(): AgentInterface;
}
