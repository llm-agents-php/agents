<?php

declare(strict_types=1);

namespace LLM\Agents\LLM;

/**
 *  OptionsFactoryInterface is responsible for creating instances of Options.
 *
 *  This interface allows for flexible creation of option sets, potentially with
 *  default values or initial configurations. It supports dependency injection
 *  and makes testing easier by abstracting the creation of options.
 */
interface OptionsFactoryInterface
{
    public function create(array $options = []): OptionsInterface;
}
