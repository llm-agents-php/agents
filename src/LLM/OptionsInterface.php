<?php

declare(strict_types=1);

namespace LLM\Agents\LLM;

interface OptionsInterface extends \IteratorAggregate
{
    public function has(string $option): bool;

    public function get(string $option, mixed $default = null): mixed;

    public function with(string $option, mixed $value): static;
}