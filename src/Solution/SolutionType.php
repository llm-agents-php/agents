<?php

declare(strict_types=1);

namespace LLM\Agents\Solution;

enum SolutionType: string
{
    case Extension = 'ext';
    case Library = 'lib';
    case Model = 'model';
    case Tool = 'tool';
    case Agent = 'agent';
    case Context = 'context';
    case Capability = 'capability';
}
