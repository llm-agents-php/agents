<?php

declare(strict_types=1);

namespace LLM\Agents\Agent\Exception;

final class MissingModelException extends AgentException
{
    public function __construct()
    {
        parent::__construct("Agent must have an associated model");
    }
}
