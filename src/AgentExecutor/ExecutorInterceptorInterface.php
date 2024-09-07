<?php

declare(strict_types=1);

namespace LLM\Agents\AgentExecutor;

use LLM\Agents\Agent\Execution;

/**
 * Interceptors allow for modifying the execution flow, adding pre- or post-processing steps,
 * or altering the behavior of the executor without changing its core implementation.
 *
 * @example
 *  Here's an example of a logging interceptor:
 *
 *  ```php
 *  final readonly class LoggingInterceptor implements ExecutorInterceptorInterface
 *  {
 *      public function __construct(private LoggerInterface $logger) {}
 *
 *      public function execute(ExecutionInput $input, InterceptorHandler $next): Execution
 *      {
 *          $this->logger->info('Executing agent: ' . $input->agent);
 *
 *          $startTime = \microtime(true);
 *          $execution = $next($input);
 *          $duration = \microtime(true) - $startTime;
 *
 *          $this->logger->info('Execution completed', [
 *              'agent' => $input->agent,
 *              'duration' => $duration,
 *              'resultType' => get_class($execution->result),
 *          ]);
 *
 *          return $execution;
 *      }
 *  }
 *  ```
 */
interface ExecutorInterceptorInterface
{
    /**
     * Execute the interceptor logic.
     *
     * This method is called as part of the execution pipeline. It can modify the input,
     * perform additional operations, or alter the execution flow.
     *
     * @param ExecutionInput $input The current execution input
     * @param InterceptorHandler $next The next handler in the pipeline
     *
     * @return Execution The result of the execution after this interceptor's processing
     */
    public function execute(
        ExecutionInput $input,
        InterceptorHandler $next,
    ): Execution;
}
