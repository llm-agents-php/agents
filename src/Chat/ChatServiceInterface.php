<?php

declare(strict_types=1);

namespace LLM\Agents\Chat;

use Cassandra\UuidInterface;
use LLM\Agents\Chat\Exception\ChatNotFoundException;

interface ChatServiceInterface
{
    /**
     * Get session by UUID.
     *
     * @throws ChatNotFoundException
     */
    public function getSession(
        UuidInterface $sessionUuid,
    ): SessionInterface;

    public function updateSession(
        SessionInterface $session,
    ): void;

    /**
     * Start session on context.
     */
    public function startSession(
        UuidInterface $accountUuid,
        string $agentName,
        string|\Stringable $message,
    ): UuidInterface;

    /**
     * Ask question to chat.
     */
    public function ask(
        UuidInterface $sessionUuid,
        string|\Stringable $message,
    ): UuidInterface;

    /**
     * Close session.
     */
    public function closeSession(
        UuidInterface $sessionUuid,
    ): void;
}
