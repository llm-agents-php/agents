<?php

declare(strict_types=1);

namespace LLM\Agents\Agent;

use LLM\Agents\LLM\AgentPromptGeneratorInterface;
use LLM\Agents\LLM\ContextFactoryInterface;
use LLM\Agents\LLM\ContextInterface;
use LLM\Agents\LLM\LLMInterface;
use LLM\Agents\LLM\OptionsFactoryInterface;
use LLM\Agents\LLM\Prompt\Chat\Prompt;
use LLM\Agents\LLM\Prompt\Tool;
use LLM\Agents\LLM\Response\ChatResponse;
use LLM\Agents\LLM\Response\ToolCalledResponse;
use LLM\Agents\Solution\ToolLink;
use LLM\Agents\Tool\SchemaMapperInterface;
use LLM\Agents\Tool\ToolInterface;
use LLM\Agents\Tool\ToolRepositoryInterface;

final readonly class AgentExecutor
{
    public function __construct(
        private LLMInterface $llm,
        private OptionsFactoryInterface $optionsFactory,
        private ContextFactoryInterface $contextFactory,
        private AgentPromptGeneratorInterface $promptGenerator,
        private ToolRepositoryInterface $tools,
        private AgentRepositoryInterface $agents,
        private SchemaMapperInterface $schemaMapper,
    ) {}

    public function execute(
        string $agent,
        string|\Stringable|Prompt $prompt,
        ?ContextInterface $context = null,
        ?array $sessionContext = null,
    ): Execution {
        $agent = $this->agents->get($agent);

        $context ??= $this->contextFactory->create();

        if (!$prompt instanceof Prompt) {
            $prompt = $this->promptGenerator->generate($agent, $prompt, $sessionContext);
        }

        $model = $agent->getModel();

        $tools = \array_map(
            fn(ToolLink $tool): ToolInterface => $this->tools->get($tool->getName()),
            $agent->getTools(),
        );

        $options = $this->optionsFactory
            ->create()
            ->with('model', $model->name)
            ->with(
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
            );

        foreach ($agent->getConfiguration() as $configuration) {
            $options = $options->with($configuration->key, $configuration->content);
        }

        $result = $this->llm->generate($context, $prompt, $options);

        if ($result instanceof ChatResponse || $result instanceof ToolCalledResponse) {
            $prompt = $prompt->withAddedMessage($result->toMessage());
        }

        return new Execution(
            result: $result,
            prompt: $prompt,
        );
    }
}
