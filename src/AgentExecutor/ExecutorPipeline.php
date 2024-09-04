<?php

declare(strict_types=1);

namespace LLM\Agents\AgentExecutor;

use LLM\Agents\Agent\Execution;
use LLM\Agents\AgentExecutor\Exception\InvalidPromptException;
use LLM\Agents\LLM\ContextFactoryInterface;
use LLM\Agents\LLM\ContextInterface;
use LLM\Agents\LLM\LLMInterface;
use LLM\Agents\LLM\OptionsFactoryInterface;
use LLM\Agents\LLM\OptionsInterface;
use LLM\Agents\LLM\Prompt\Chat\Prompt;
use LLM\Agents\LLM\Prompt\Context;
use LLM\Agents\LLM\Prompt\PromptInterface;
use LLM\Agents\LLM\PromptContextInterface;

final class ExecutorPipeline implements ExecutorInterface
{
    /** @var ExecutorInterceptorInterface[] */
    private array $interceptors = [];
    private int $offset = 0;

    public function __construct(
        private readonly LLMInterface $llm,
        private readonly OptionsFactoryInterface $optionsFactory,
        private readonly ContextFactoryInterface $contextFactory,
    ) {}

    public function execute(
        string $agent,
        \Stringable|string|Prompt $prompt,
        ?ContextInterface $context = null,
        ?OptionsInterface $options = null,
        PromptContextInterface $promptContext = new Context(),
    ): Execution {
        $context ??= $this->contextFactory->create();
        $options ??= $this->optionsFactory->create();

        if (!isset($this->interceptors[$this->offset])) {
            if (!$prompt instanceof PromptInterface) {
                throw new InvalidPromptException(\sprintf('Prompt must be an instance of %s', PromptInterface::class));
            }

            $result = $this->llm->generate($context, $prompt, $options);

            return new Execution(
                result: $result,
                prompt: $prompt,
            );
        }

        return $this->interceptors[$this->offset]->execute(
            input: new ExecutionInput(
                agent: $agent,
                prompt: $prompt,
                context: $context,
                options: $options,
                promptContext: $promptContext,
            ),
            next: new InterceptorHandler(executor: $this->next()),
        );
    }

    public function withInterceptor(ExecutorInterceptorInterface ...$interceptor): ExecutorInterface
    {
        $pipeline = clone $this;
        $pipeline->interceptors = \array_merge($this->interceptors, $interceptor);

        return $pipeline;
    }

    private function next(): self
    {
        $pipeline = clone $this;
        $pipeline->offset++;
        return $pipeline;
    }
}
