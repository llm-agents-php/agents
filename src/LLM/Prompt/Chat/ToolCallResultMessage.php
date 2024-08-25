<?php

declare(strict_types=1);

namespace LLM\Agents\LLM\Prompt\Chat;

use LLM\Agents\LLM\Prompt\MessageInterface;

final readonly class ToolCallResultMessage implements MessageInterface, HasRoleInterface
{
    public function __construct(
        public string $id,
        public array $content,
        public Role $role = Role::Tool,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'role' => $this->role->value,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            content: $data['content'],
            role: Role::tryFrom($data['role']),
        );
    }

    public function getRole(): Role
    {
        return $this->role;
    }
}