<?php

declare(strict_types=1);

namespace LLM\Agents\Workflow;

use LLM\Agents\Agent\AgentInterface;
use LLM\Agents\Agent\AgentRepositoryInterface;
use LLM\Agents\Agent\HasCapabilitiesInterface;

final readonly class TaskRouter
{
    public function __construct(
        private AgentRepositoryInterface $agentRepository,
    ) {}

    public function routeTask(Task $task): ?AgentInterface
    {
        $suitableAgents = $this->agentRepository->findByCapabilityKey($task->primaryCapabilityKey);

        $suitableAgents = \iterator_to_array($suitableAgents);

        $suitableAgents = \array_filter(
            $suitableAgents,
            static fn(AgentInterface $agent) => $agent instanceof HasCapabilitiesInterface,
        );

        if (empty($suitableAgents)) {
            return null;
        }

        \usort($suitableAgents, function (HasCapabilitiesInterface $a, HasCapabilitiesInterface $b) use ($task) {
            return $this->calculateAgentSuitability($b, $task) - $this->calculateAgentSuitability($a, $task);
        });

        return $suitableAgents[0];
    }

    private function calculateAgentSuitability(HasCapabilitiesInterface $agent, Task $task): int
    {
        $suitability = 0;

        if ($agent->hasCapability($task->primaryCapabilityKey)) {
            $suitability += 10;
        }

        foreach ($task->additionalCapabilityKeys as $capabilityKey) {
            if ($agent->hasCapability($capabilityKey)) {
                $suitability += 5;
            }
        }

        return $suitability;
    }
}
