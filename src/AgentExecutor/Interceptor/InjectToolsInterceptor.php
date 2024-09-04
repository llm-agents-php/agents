<?php

declare(strict_types=1);

namespace LLM\Agents\AgentExecutor\Interceptor;

use LLM\Agents\Agent\AgentRepositoryInterface;
use LLM\Agents\Agent\Execution;
use LLM\Agents\AgentExecutor\ExecutorInterceptorInterface;
use LLM\Agents\AgentExecutor\ExecutorInterface;
use LLM\Agents\LLM\ContextInterface;
use LLM\Agents\LLM\OptionsInterface;
use LLM\Agents\LLM\Prompt\Chat\Prompt;
use LLM\Agents\LLM\Prompt\Tool;
use LLM\Agents\LLM\PromptContextInterface;
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
        string $agent,
        \Stringable|string|Prompt $prompt,
        ContextInterface $context,
        OptionsInterface $options,
        PromptContextInterface $promptContext,
        ExecutorInterface $next,
    ): Execution {
        $agent = $this->agents->get($agent);

        $tools = \array_map(
            fn(ToolLink $tool): ToolInterface => $this->tools->get($tool->getName()),
            $agent->getTools(),
        );

        return $next->execute(
            agent: $agent->getKey(),
            prompt: $prompt,
            context: $context,
            options: $options->with(
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
            promptContext: $promptContext,
        );
    }
}

