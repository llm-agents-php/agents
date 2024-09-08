<?php

declare(strict_types=1);

namespace LLM\Agents\Agent;

use LLM\Agents\Solution\Capability;

interface HasCapabilitiesInterface
{
    /**
     * Returns the capabilities of the agent.
     *
     * @return array<Capability>
     */
    public function getCapabilities(): array;

    /**
     * Checks if the agent has a capability with the given key.
     */
    public function hasCapability(string $key): bool;
}
