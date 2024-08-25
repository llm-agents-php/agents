<?php

declare(strict_types=1);

namespace LLM\Agents\LLM\Prompt\Chat;

use LLM\Agents\LLM\Prompt\MessageInterface;
use LLM\Agents\LLM\Prompt\SerializableInterface;

readonly class ChatMessage implements MessageInterface, HasRoleInterface, SerializableInterface
{
    public function __construct(
        public string|array $content,
        public Role $role = Role::User,
    ) {}

    public function toArray(): array
    {
        return [
            'content' => $this->content,
            'role' => $this->role->value,
        ];
    }

    public static function fromArray(array $data): static
    {
        return new ChatMessage(
            content: $data['content'],
            role: Role::tryFrom($data['role']),
        );
    }

    public function getRole(): Role
    {
        return $this->role;
    }
}
