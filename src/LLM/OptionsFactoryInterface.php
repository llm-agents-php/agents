<?php

declare(strict_types=1);

namespace LLM\Agents\LLM;

interface OptionsFactoryInterface
{
    public function create(): OptionsInterface;
}
