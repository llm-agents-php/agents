<?php

declare(strict_types=1);

namespace LLM\Agents\LLM\Prompt\Chat;

use LLM\Agents\LLM\Prompt\MessageInterface;
use LLM\Agents\LLM\Prompt\SerializableInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

readonly class ChatMessage implements MessageInterface, HasRoleInterface, SerializableInterface
{
    public UuidInterface $uuid;

    public function __construct(
        public string|array $content,
        public Role $role = Role::User,
        ?UuidInterface $uuid = null,
    ) {
        $this->uuid = $uuid ?? Uuid::uuid4();
    }

    public function toArray(): array
    {
        return [
            'content' => $this->content,
            'role' => $this->role->value,
            'uuid' => $this->uuid->toString(),
        ];
    }

    public static function fromArray(array $data): static
    {
        return new ChatMessage(
            content: $data['content'],
            role: Role::tryFrom($data['role']),
            uuid: Uuid::fromString($data['uuid']),
        );
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
