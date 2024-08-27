<?php

declare(strict_types=1);

namespace LLM\Agents\LLM\Prompt\Chat;

use LLM\Agents\LLM\Prompt\MessageInterface;
use LLM\Agents\LLM\Prompt\SerializableInterface;

final readonly class ToolCallResultMessage implements MessageInterface, HasRoleInterface, SerializableInterface
{
    public function __construct(
        public string $id,
        public array $content,
        public Role $role = Role::Tool,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            content: $data['content'],
            role: Role::tryFrom($data['role']),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'role' => $this->role->value,
        ];
    }

    public function getRole(): Role
    {
        return $this->role;
    }
}
