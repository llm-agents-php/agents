<?php

declare(strict_types=1);

namespace LLM\Agents\LLM;

interface ContextFactoryInterface
{
    public function create(): ContextInterface;
}
