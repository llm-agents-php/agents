<?php

declare(strict_types=1);

namespace LLM\Agents\AgentExecutor\Interceptor;

use LLM\Agents\Agent\AgentRepositoryInterface;
use LLM\Agents\Agent\Execution;
use LLM\Agents\AgentExecutor\ExecutionInput;
use LLM\Agents\AgentExecutor\ExecutorInterceptorInterface;
use LLM\Agents\AgentExecutor\InterceptorHandler;
use LLM\Agents\LLM\Prompt\Tool;
use LLM\Agents\Solution\ToolLink;
use LLM\Agents\Tool\SchemaMapperInterface;
use LLM\Agents\Tool\ToolInterface;
use LLM\Agents\Tool\ToolRepositoryInterface;

final readonly class InjectToolsInterceptor implements ExecutorInterceptorInterface
{
    public function __construct(
        private AgentRepositoryInterface $agents,
        private ToolRepositoryInterface $tools,
        private SchemaMapperInterface $schemaMapper,
    ) {}

    public function execute(
        ExecutionInput $input,
        InterceptorHandler $next,
    ): Execution {
        $agent = $this->agents->get($input->agent);

        $tools = \array_map(
            fn(ToolLink $tool): ToolInterface => $this->tools->get($tool->getName()),
            $agent->getTools(),
        );

        return $next(
            $input->withOptions(
                $input->options->with(
                    'tools',
                    \array_map(
                        fn(ToolInterface $tool): Tool => $this->buildTool($tool),
                        $tools,
                    ),
                ),
            ),
        );
    }

    private function buildTool(ToolInterface $tool): Tool
    {
        // In some cases, the input schema can be a class name or a JSON schema string.
        // For example, the input schema can be a class name when the tool is a PHP tool.
        // Or it can be a JSON schema string when the tool is a Python tool stored in the database.
        // So we need to handle both cases.
        if (\class_exists($tool->getInputSchema())) {
            $parameters = $this->schemaMapper->toJsonSchema($tool->getInputSchema());
        } else {
            $parameters = \json_decode($tool->getInputSchema(), true);
        }

        return new Tool(
            name: $tool->getName(),
            description: $tool->getDescription(),
            parameters: $parameters,
            strict: false,
        );
    }
}

