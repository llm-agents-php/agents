<?php

declare(strict_types=1);

namespace LLM\Agents\AgentExecutor\Interceptor;

use LLM\Agents\Agent\AgentRepositoryInterface;
use LLM\Agents\Agent\Execution;
use LLM\Agents\AgentExecutor\ExecutionInput;
use LLM\Agents\AgentExecutor\ExecutorInterceptorInterface;
use LLM\Agents\AgentExecutor\ExecutorInterface;
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
        ExecutorInterface $next,
    ): Execution {
        $agent = $this->agents->get($input->agent);

        $tools = \array_map(
            fn(ToolLink $tool): ToolInterface => $this->tools->get($tool->getName()),
            $agent->getTools(),
        );

        return $next->execute(
            agent: $input->agent,
            prompt: $input->prompt,
            context: $input->context,
            options: $input->options->with(
                'tools',
                \array_map(
                    fn(ToolInterface $tool): Tool => new Tool(
                        name: $tool->getName(),
                        description: $tool->getDescription(),
                        parameters: $this->schemaMapper->toJsonSchema($tool->getInputSchema()),
                        strict: false,
                    ),
                    $tools,
                ),
            ),
            promptContext: $input->promptContext,
        );
    }
}

