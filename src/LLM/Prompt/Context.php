<?php

declare(strict_types=1);

namespace LLM\Agents\LLM\Prompt;

use LLM\Agents\LLM\PromptContextInterface;

class Context implements PromptContextInterface
{
    private array $values = [];

    public function addValues(array $values): static
    {
        $this->values = \array_merge($this->values, $values);

        return $this;
    }

    public function getValues(): array
    {
        return $this->values;
    }
}
