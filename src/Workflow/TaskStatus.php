<?php

declare(strict_types=1);

namespace LLM\Agents\Workflow;

enum TaskStatus: string
{
    case Pending = 'pending';
    case InProgress = 'in_progress';
    case Completed = 'completed';
    case Failed = 'failed';
}
