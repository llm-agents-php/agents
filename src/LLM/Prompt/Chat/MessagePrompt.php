<?php

declare(strict_types=1);

namespace LLM\Agents\LLM\Prompt\Chat;

use LLM\Agents\LLM\Prompt\DataPrompt;
use LLM\Agents\LLM\Prompt\FormatterInterface;
use LLM\Agents\LLM\Prompt\FString;
use LLM\Agents\LLM\Prompt\SerializableInterface;
use LLM\Agents\LLM\Prompt\StringPrompt;
use LLM\Agents\LLM\Prompt\StringPromptInterface;
use Stringable;

use function is_string;

final readonly class MessagePrompt implements StringPromptInterface, HasRoleInterface, SerializableInterface
{
    public function __construct(
        private StringPromptInterface $prompt,
        public Role $role = Role::User,
        private array $with = [],
    ) {
    }

    public static function system(
        StringPromptInterface|string|Stringable $prompt,
        array $values = [],
        array $with = [],
    ): self {
        if (is_string($prompt)) {
            $prompt = new StringPrompt($prompt);
        }

        return new self($prompt->withValues($values), Role::System, $with);
    }

    public static function user(
        StringPromptInterface|string|Stringable $prompt,
        array $values = [],
        array $with = [],
    ): self {
        if (is_string($prompt)) {
            $prompt = new StringPrompt($prompt);
        }

        return new self($prompt->withValues($values), Role::User, $with);
    }

    public static function assistant(
        StringPromptInterface|string|Stringable $prompt,
        array $values = [],
        array $with = [],
    ): self {
        if (is_string($prompt)) {
            $prompt = new StringPrompt($prompt);
        }

        return new self($prompt->withValues($values), Role::Assistant, $with);
    }

    public static function fromArray(array $data, FormatterInterface $formatter = new FString()): self
    {
        $prompt = $data['prompt'];

        if (isset($prompt['template'])) {
            return new self(
                StringPrompt::fromArray($prompt, $formatter),
                Role::fromValue($data['role']),
            );
        }

        return new self(
            DataPrompt::fromArray($prompt),
            Role::fromValue($data['role']),
        );
    }

    public function toChatMessage(array $parameters = []): ?ChatMessage
    {
        $prompt = $this->prompt;

        foreach ($this->with as $var) {
            if (! isset($parameters[$var]) || empty($parameters[$var])) {
                // condition failed
                return null;
            }
        }

        return new ChatMessage(
            $prompt instanceof DataPrompt ? $prompt->toArray() : $prompt->format($parameters),
            $this->role,
        );
    }

    public function withValues(array $values): self
    {
        return new self($this->prompt->withValues($values), $this->role);
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
        ];
    }

    public function getRole(): Role
    {
        return $this->role;
    }
}
