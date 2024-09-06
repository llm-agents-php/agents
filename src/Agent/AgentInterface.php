<?php

declare(strict_types=1);

namespace LLM\Agents\Agent;

use LLM\Agents\Solution\AgentLink;
use LLM\Agents\Solution\Model;
use LLM\Agents\Solution\Solution;
use LLM\Agents\Solution\SolutionMetadata;
use LLM\Agents\Solution\ToolLink;

/**
 * @psalm-type TAssociation = Solution|Model|ToolLink|AgentLink
 */
interface AgentInterface
{
    public function getKey(): string;

    public function getName(): string;

    public function getDescription(): ?string;

    public function getInstruction(): string;

    /**
     * @return array<ToolLink>
     */
    public function getTools(): array;

    public function getModel(): Model;

    /**
     * @return array<SolutionMetadata>
     */
    public function getMemory(): array;

    /**
     * @return array<SolutionMetadata>
     */
    public function getPrompts(): array;

    /**
     * @return array<SolutionMetadata>
     */
    public function getConfiguration(): array;
}
