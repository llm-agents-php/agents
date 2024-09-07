<?php

declare(strict_types=1);

namespace LLM\Agents\Agent;

use LLM\Agents\Solution\AgentLink;
use LLM\Agents\Solution\Model;
use LLM\Agents\Solution\Solution;
use LLM\Agents\Solution\SolutionMetadata;
use LLM\Agents\Solution\ToolLink;

/**
 * Represents an AI agent capable of performing tasks, making decisions,
 * and interacting with various tools and other agents.
 * @psalm-type TAssociation = Solution|Model|ToolLink|AgentLink
 */
interface AgentInterface
{
    /**
     * Get the unique key identifier for the agent.
     */
    public function getKey(): string;

    /**
     * Get the human-readable name of the agent.
     */
    public function getName(): string;

    /**
     * Get the description of the agent's purpose and capabilities.
     */
    public function getDescription(): ?string;

    /**
     * Get the primary instruction set for the agent.
     */
    public function getInstruction(): string;

    /**
     * Get the language model associated with this agent.
     */
    public function getModel(): Model;

    /**
     * Get the agent's memory, containing learned information and experiences.
     *
     * @return array<SolutionMetadata>
     */
    public function getMemory(): array;

    /**
     * Get the list of predefined prompts for the agent.
     *
     * @return array<SolutionMetadata>
     */
    public function getPrompts(): array;

    /**
     * Get the agent's configuration settings.
     *
     * This method returns an array of configuration settings for the agent,
     * which can include parameters for the LLM (Language Model) client configuration.
     * These settings may affect how the agent interacts with the language model,
     * including parameters like temperature, max tokens, and other model-specific options.
     *
     * @return array<SolutionMetadata>
     *
     * @example
     * [
     *     new SolutionMetadata(MetadataType::Configuration, 'temperature', 0.7),
     *     new SolutionMetadata(MetadataType::Configuration, 'max_tokens', 150),
     *     new SolutionMetadata(MetadataType::Configuration, 'top_p', 1),
     * ]
     */
    public function getConfiguration(): array;
}
