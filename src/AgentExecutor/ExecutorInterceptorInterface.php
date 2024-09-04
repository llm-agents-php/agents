<?php

declare(strict_types=1);

namespace LLM\Agents\AgentExecutor;

use LLM\Agents\Agent\Execution;

interface ExecutorInterceptorInterface
{
    public function execute(
        ExecutionInput $input,
        ExecutorInterface $next,
    ): Execution;
}
