<?php

declare(strict_types=1);

namespace LLM\Agents\LLM;

interface PromptContextInterface
{
    public function addValues(array $values): static;

    public function getValues(): array;
}
