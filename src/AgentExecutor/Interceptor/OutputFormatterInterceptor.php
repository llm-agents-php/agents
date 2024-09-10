<?php

declare(strict_types=1);

namespace LLM\Agents\AgentExecutor\Interceptor;

use LLM\Agents\Agent\Execution;
use LLM\Agents\AgentExecutor\ExecutionInput;
use LLM\Agents\AgentExecutor\ExecutorInterceptorInterface;
use LLM\Agents\AgentExecutor\InterceptorHandler;
use LLM\Agents\LLM\Exception\FormatterException;
use LLM\Agents\LLM\Output\EnumFormatter;
use LLM\Agents\LLM\Output\FormatterInterface;
use LLM\Agents\LLM\Output\JsonSchemaFormatter;
use LLM\Agents\LLM\Prompt\PromptInterface;
use LLM\Agents\LLM\Response\ChatResponse;

/**
 * This interceptor is responsible for formatting the output of the agent based on the provided output formatter.
 */
final readonly class OutputFormatterInterceptor implements ExecutorInterceptorInterface
{
    public function __construct(
        private JsonSchemaFormatter $jsonSchemaFormatter,
    ) {}

    public function execute(ExecutionInput $input, InterceptorHandler $next): Execution
    {
        $outputFormatter = $input->options->get('output_formatter');

        if ($outputFormatter === null) {
            return $next($input);
        }

        if (!$input->prompt instanceof PromptInterface) {
            throw new FormatterException('Prompt must implement PromptInterface');
        }

        $input = $input->withPrompt(
            $input->prompt->withValues(
                ['output_format_instruction' => $outputFormatter->getInstruction()],
            ),
        );

        if ($outputFormatter instanceof FormatterInterface) {
            return $this->formatResponse($next($input), $outputFormatter);
        }

        if (\is_string($outputFormatter)) {
            return $this->formatResponse($next($input), $this->createFormatter($outputFormatter));
        }
    }

    private function formatResponse(Execution $execution, FormatterInterface $outputFormatter): Execution
    {
        $result = $execution->result;

        if (!$result instanceof ChatResponse) {
            return $execution;
        }

        return new Execution(
            result: new ChatResponse(
                content: $outputFormatter->format($result->content),
            ),
            prompt: $execution->prompt,
        );
    }

    /**
     * @param non-empty-string|class-string $schema
     */
    private function createFormatter(string $schema): FormatterInterface
    {
        // If the schema is an existing class, check if it is an enum.
        if (\class_exists($schema)) {
            $refl = new \ReflectionClass($schema);
            if ($refl->isEnum()) {
                return new EnumFormatter($refl->getName());
            }
        }

        return $this->jsonSchemaFormatter->withJsonSchema($schema);
    }
}
