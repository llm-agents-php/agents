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
        return $this->selectMostSuitableAgent(
            agents: $this->agentRepository->findByCapabilityKey($task->primaryCapabilityKey),
            task: $task,
        );
    }

    private function selectMostSuitableAgent(iterable $agents, Task $task): ?AgentInterface
    {
        $suitableAgents = [];

        // Filter agents that have the primary capability and collect suitability scores
        foreach ($agents as $agent) {
            if ($agent instanceof HasCapabilitiesInterface && $agent->hasCapability($task->primaryCapabilityKey)) {
                $suitabilityScore = $this->calculateAgentSuitability($agent, $task);
                $suitableAgents[] = ['agent' => $agent, 'score' => $suitabilityScore];
            }
        }

        // Sort agents based on the calculated suitability scores in descending order
        \usort($suitableAgents, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        // Return the most suitable agent or null if no suitable agents were found
        return !empty($suitableAgents) ? $suitableAgents[0]['agent'] : null;
    }

    private function calculateAgentSuitability(AgentInterface $agent, Task $task): int
    {
        $suitabilityScore = 0;

        // Primary capability is a must-have, add a high base score
        if ($agent->hasCapability($task->primaryCapabilityKey)) {
            $suitabilityScore += 10;
        }

        // Additional capabilities contribute less to the score but are still important
        foreach ($task->additionalCapabilityKeys as $capabilityKey) {
            if ($agent->hasCapability($capabilityKey)) {
                $suitabilityScore += 3;
            }
        }

        return $suitabilityScore;
    }
}
