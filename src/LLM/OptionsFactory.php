<?php

declare(strict_types=1);

namespace LLM\Agents\LLM;

final class OptionsFactory implements OptionsFactoryInterface
{
    public function create(array $options = []): OptionsInterface
    {
        return new Options($options);
    }
}
