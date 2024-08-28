<?php

declare(strict_types=1);

namespace LLM\Agents\LLM\Prompt\Chat;

use LLM\Agents\LLM\Prompt\MessageInterface;
use LLM\Agents\LLM\Prompt\SerializableInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final readonly class ToolCallResultMessage implements MessageInterface, HasRoleInterface, SerializableInterface
{
    public UuidInterface $uuid;

    public function __construct(
        public string $id,
        public array $content,
        public Role $role = Role::Tool,
        ?UuidInterface $uuid = null,
    ) {
        $this->uuid = $uuid ?? Uuid::uuid4();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'role' => $this->role->value,
            'uuid' => $this->uuid->toString(),
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
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