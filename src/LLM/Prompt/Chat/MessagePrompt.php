<?php

declare(strict_types=1);

namespace LLM\Agents\LLM\Prompt\Chat;

use LLM\Agents\LLM\Prompt\DataPrompt;
use LLM\Agents\LLM\Prompt\FormatterInterface;
use LLM\Agents\LLM\Prompt\FString;
use LLM\Agents\LLM\Prompt\SerializableInterface;
use LLM\Agents\LLM\Prompt\StringPrompt;
use LLM\Agents\LLM\Prompt\StringPromptInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final readonly class MessagePrompt implements StringPromptInterface, HasRoleInterface, SerializableInterface
{
    public UuidInterface $uuid;

    public static function system(
        StringPromptInterface|string|\Stringable $prompt,
        array $values = [],
        array $with = [],
        ?UuidInterface $uuid = null,
    ): self {
        if (\is_string($prompt)) {
            $prompt = new StringPrompt($prompt);
        }

        return new self(
            prompt: $prompt->withValues($values),
            role: Role::System,
            with: $with,
            uuid: $uuid,
        );
    }

    public static function user(
        StringPromptInterface|string|\Stringable $prompt,
        array $values = [],
        array $with = [],
        ?UuidInterface $uuid = null,
    ): self {
        if (\is_string($prompt)) {
            $prompt = new StringPrompt($prompt);
        }

        return new self(
            prompt: $prompt->withValues($values),
            role: Role::User,
            with: $with,
            uuid: $uuid,
        );
    }

    public static function assistant(
        StringPromptInterface|string|\Stringable $prompt,
        array $values = [],
        array $with = [],
        ?UuidInterface $uuid = null,
    ): self {
        if (\is_string($prompt)) {
            $prompt = new StringPrompt($prompt);
        }

        return new self(
            prompt: $prompt->withValues($values),
            role: Role::Assistant,
            with: $with,
            uuid: $uuid,
        );
    }

    public static function fromArray(array $data, FormatterInterface $formatter = new FString()): self
    {
        $prompt = $data['prompt'];

        if (isset($prompt['template'])) {
            return new self(
                prompt: StringPrompt::fromArray($prompt, $formatter),
                role: Role::fromValue($data['role']),
                uuid: Uuid::fromString($data['uuid']),
            );
        }

        return new self(
            prompt: DataPrompt::fromArray($prompt),
            role: Role::fromValue($data['role']),
            uuid: Uuid::fromString($data['uuid']),
        );
    }

    public function __construct(
        private StringPromptInterface $prompt,
        public Role $role = Role::User,
        private array $with = [],
        ?UuidInterface $uuid = null,
    ) {
        $this->uuid = $uuid ?? Uuid::uuid4();
    }

    public function toChatMessage(array $parameters = []): ?ChatMessage
    {
        $prompt = $this->prompt;

        foreach ($this->with as $var) {
            if (!isset($parameters[$var]) || empty($parameters[$var])) {
                // condition failed
                return null;
            }
        }

        return new ChatMessage(
            content: $prompt instanceof DataPrompt ? $prompt->toArray() : $prompt->format($parameters),
            role: $this->role,
            uuid: $this->uuid,
        );
    }

    public function withValues(array $values): self
    {
        return new self(
            prompt: $this->prompt->withValues($values),
            role: $this->role,
            uuid: $this->uuid,
        );
    }

    public function format(array $variables = []): string
    {
        return $this->prompt->format($variables);
    }

    public function __toString(): string
    {
        return $this->format();
    }

    public function toArray(): array
    {
        return [
            'prompt' => $this->prompt->toArray(),
            'role' => $this->role->value,
            'uuid' => $this->uuid->toString(),
        ];
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }
}
