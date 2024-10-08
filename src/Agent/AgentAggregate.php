<?php

declare(strict_types=1);

namespace LLM\Agents\Agent;

use LLM\Agents\Agent\Exception\AgentModelException;
use LLM\Agents\Agent\Exception\MissingModelException;
use LLM\Agents\Embeddings\HasLinkedContextSourcesInterface;
use LLM\Agents\Solution\AgentLink;
use LLM\Agents\Solution\ContextSourceLink;
use LLM\Agents\Solution\MetadataType;
use LLM\Agents\Solution\Model;
use LLM\Agents\Solution\Solution;
use LLM\Agents\Solution\SolutionMetadata;
use LLM\Agents\Solution\SolutionType;
use LLM\Agents\Solution\ToolLink;

/**
 * @psalm-type TAssociation = Solution|Model|ToolLink|AgentLink
 */
class AgentAggregate implements AgentInterface,
                                HasLinkedAgentsInterface,
                                HasLinkedToolsInterface,
                                HasLinkedContextSourcesInterface,
                                MetadataAwareInterface
{
    /** @var array<TAssociation> */
    private array $associations = [];

    public function __construct(
        private readonly Agent $agent,
    ) {}

    public function getName(): string
    {
        return $this->agent->name;
    }

    public function getDescription(): ?string
    {
        return $this->agent->description;
    }

    public function getKey(): string
    {
        return $this->agent->key;
    }

    public function getInstruction(): string
    {
        return $this->agent->instruction;
    }

    public function getTools(): array
    {
        return \array_filter(
            $this->associations,
            static fn(Solution $association): bool => $association instanceof ToolLink,
        );
    }

    public function getContextSources(): array
    {
        return \array_filter(
            $this->associations,
            static fn(Solution $association): bool => $association instanceof ContextSourceLink,
        );
    }

    public function getAgents(): array
    {
        return \array_filter(
            $this->associations,
            static fn(Solution $association): bool => $association instanceof AgentLink,
        );
    }

    public function getModel(): Model
    {
        foreach ($this->associations as $association) {
            if ($association instanceof Model) {
                return $association;
            }
        }

        throw new MissingModelException();
    }

    public function getMemory(): array
    {
        return \array_values(
            \array_filter(
                $this->getMetadata(),
                static fn(SolutionMetadata $metadata): bool => $metadata->type === MetadataType::Memory,
            ),
        );
    }

    public function getPrompts(): array
    {
        return \array_values(
            \array_filter(
                $this->getMetadata(),
                static fn(SolutionMetadata $metadata): bool => $metadata->type === MetadataType::Prompt,
            ),
        );
    }

    /**
     * @return array<SolutionMetadata>
     */
    public function getConfiguration(): array
    {
        return \array_values(
            \array_filter(
                $this->getMetadata(),
                static fn(SolutionMetadata $metadata): bool => $metadata->type === MetadataType::Configuration,
            ),
        );
    }

    public function addAssociation(Solution $association): void
    {
        $this->validateDependency($association);

        $this->associations[] = $association;
    }

    public function addMetadata(SolutionMetadata ...$metadata): void
    {
        $this->agent->addMetadata(...$metadata);
    }

    private function validateDependency(Solution $association): void
    {
        if ($association instanceof Model) {
            foreach ($this->associations as $a) {
                if ($a->type === SolutionType::Model) {
                    throw new AgentModelException('Agent already has a model associated');
                }
            }
        }
    }

    public function getMetadata(): array
    {
        $metadata = $this->agent->getMetadata();

        foreach ($this->associations as $association) {
            if ($association instanceof MetadataAwareInterface) {
                $metadata = \array_merge($metadata, $association->getMetadata());
            }
        }

        return $metadata;
    }
}
