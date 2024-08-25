<?php

declare(strict_types=1);

namespace LLM\Agents\LLM\Prompt\Chat;

interface HasRoleInterface
{
    public function getRole(): Role;
}