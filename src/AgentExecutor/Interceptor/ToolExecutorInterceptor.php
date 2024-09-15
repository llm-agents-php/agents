<?php

declare(strict_types=1);

namespace LLM\Agents\AgentExecutor\Interceptor;

use LLM\Agents\Agent\Execution;
use LLM\Agents\AgentExecutor\ExecutionInput;
use LLM\Agents\AgentExecutor\ExecutorInterceptorInterface;
use LLM\Agents\AgentExecutor\InterceptorHandler;
use LLM\Agents\LLM\Prompt\Chat\ToolCallResultMessage;
use LLM\Agents\LLM\Prompt\Chat\ToolsCallResultResponse;
use LLM\Agents\LLM\Response\ToolCall;
use LLM\Agents\LLM\Response\ToolCalledResponse;
use LLM\Agents\Tool\ToolExecutor;

/**
 * This interceptor is responsible for calling the tools if LLM asks for it. After calling the tools, it adds the
 * tools responses to the prompt history and return the result of tools execution to the LLM.
 *
 * If the option 'return_tool_result' is set to true, the interceptor will return the tools result instead of adding
 * it to the prompt.
 */
final readonly class ToolExecutorInterceptor implements ExecutorInterceptorInterface
{
    public function __construct(
        private ToolExecutor $toolExecutor,
    ) {}

    public function execute(ExecutionInput $input, InterceptorHandler $next): Execution
    {
        $execution = $next($input);

        // Check if we should return the tool result instead of adding it to the prompt.
        $shouldReturnToolResult = $input->options->get('return_tool_result', false);

        while (true) {
            $result = $execution->result;
            $prompt = $execution->prompt;

            // If the result is a ToolCalledResponse, we need to call the tools.
            if ($result instanceof ToolCalledResponse) {
                // First, call all tools.
                $toolsResponse = [];
                foreach ($result->tools as $tool) {
                    $toolsResponse[] = $this->callTool($tool);
                }

                // In some cases we want to return the tool result instead of adding it to the prompt.
                // We don't need an answer from the LLM, all we wanted is to ask LLM to execute desired tools.
                if ($shouldReturnToolResult) {
                    return new Execution(
                        result: new ToolsCallResultResponse(results: $toolsResponse),
                        prompt: $prompt,
                    );
                }

                // Then add the tools responses to the prompt.
                foreach ($toolsResponse as $toolResponse) {
                    $input = $input->withPrompt($prompt->withAddedMessage($toolResponse));
                }

                // Continue to the next execution.
                $execution = $next($input);

                continue;
            }

            return $execution;
        }
    }

    private function callTool(ToolCall $tool): ToolCallResultMessage
    {
        $functionResult = $this->toolExecutor->execute($tool->name, $tool->arguments);

        return new ToolCallResultMessage(
            id: $tool->id,
            content: [$functionResult],
        );
    }
}
