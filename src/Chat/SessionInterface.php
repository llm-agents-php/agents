<?php

declare(strict_types=1);

namespace LLM\Agents\Chat;

interface SessionInterface
{
    public function updateHistory(array $messages): void;

    public function isFinished(): bool;
}