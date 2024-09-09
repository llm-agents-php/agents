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

final readonly class ToolExecutorInterceptor implements ExecutorInterceptorInterface
{
    public function __construct(
        private ToolExecutor $toolExecutor,
    ) {}

    public function execute(ExecutionInput $input, InterceptorHandler $next): Execution
    {
        $execution = $next($input);

        $shouldReturnToolResult = $input->options->get('return_tool_result', false);

        while (true) {
            $result = $execution->result;
            $prompt = $execution->prompt;

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
