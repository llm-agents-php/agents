<?php

declare(strict_types=1);

namespace LLM\Agents\Solution;

enum MetadataType: string
{
    case Configuration = 'configuration';
    case Memory = 'memory';
    case Prompt = 'prompt';
}
