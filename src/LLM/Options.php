<?php

declare(strict_types=1);

namespace LLM\Agents\LLM;

class Options implements OptionsInterface
{
    private array $options;

    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    public function has(string $option): bool
    {
        return isset($this->options[$option]);
    }

    public function get(string $option, mixed $default = null): mixed
    {
        return $this->options[$option] ?? $default;
    }

    public function with(string $option, mixed $value): static
    {
        $new = clone $this;
        $new->options[$option] = $value;
        return $new;
    }

    public function merge(OptionsInterface $options): static
    {
        $new = clone $this;
        foreach ($options as $key => $value) {
            $new->options[$key] = $value;
        }

        return $new;
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->options);
    }
}
