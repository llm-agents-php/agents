<?php

declare(strict_types=1);

namespace LLM\Agents\LLM\Prompt;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class StringPrompt implements StringPromptInterface, SerializableInterface
{
    private ?string $cachedPrompt = null;
    public readonly UuidInterface $uuid;

    public function __construct(
        protected string $template,
        protected array $variables = [],
        protected FormatterInterface $formatter = new FString(),
        ?UuidInterface $uuid = null,
    ) {
        $this->template = \trim($this->template);
        $this->uuid = $uuid ?? Uuid::uuid4();
    }

    public function withValues(array $values): self
    {
        $prompt = clone $this;
        $prompt->variables = \array_merge($this->variables, $values);
        $prompt->cachedPrompt = null;

        return $prompt;
    }

    public function format(array $variables = []): string
    {
        if ($variables === [] && $this->cachedPrompt !== null) {
            return $this->cachedPrompt;
        }

        // merge parameters
        $variables = \array_merge($this->variables, $variables);

        $result = $this->formatter->format($this->template, $variables);
        if ($variables === []) {
            $this->cachedPrompt = $result;
        }

        return $result;
    }

    public function __toString(): string
    {
        return $this->format();
    }

    public function toArray(): array
    {
        $result = [
            'template' => $this->template,
            'uuid' => $this->uuid->toString(),
        ];

        if ($this->variables !== []) {
            $result['variables'] = $this->variables;
        }

        return $result;
    }

    public static function fromArray(array $data, FormatterInterface $formatter = new FString()): static
    {
        return new static(
            template: $data['template'],
            variables: $data['variables'] ?? [],
            formatter: $formatter,
            uuid: Uuid::fromString($data['uuid']),
        );
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }
}
