<?php

declare(strict_types=1);

namespace LLM\Agents\Tool;

use LLM\Agents\Tool\Exception\ExecutorNotFoundException;
use LLM\Agents\Tool\LanguageExecutor\PhpLanguageExecutor;

/**
 * Executes tools based on their language and input.
 *
 * This class is responsible for executing tools of various programming languages.
 * It uses a strategy pattern to delegate execution to language-specific executors.
 */
final class ToolExecutor
{
    /** @var array<LanguageExecutorInterface> */
    private array $languageExecutors = [];

    public function __construct(
        private readonly ToolRepositoryInterface $tools,
        private readonly SchemaMapperInterface $schemaMapper,
    ) {
        $this->registerLanguageExecutor(ToolLanguage::PHP, new PhpLanguageExecutor());
    }

    /**
     * Registers a language-specific executor.
     */
    public function registerLanguageExecutor(ToolLanguage $language, LanguageExecutorInterface $executor): void
    {
        $this->languageExecutors[$language->value] = $executor;
    }

    /**
     * Executes a tool with the given input.
     *
     * @param string $tool The unique name of the tool to execute.
     * @param string $input JSON-encoded input for the tool.
     * @return string|\Stringable The result of the tool execution.
     * @throws ExecutorNotFoundException If no executor is found for the tool's language.
     */
    public function execute(string $tool, string $input): string|\Stringable
    {
        $tool = $this->tools->get($tool);

        $input = $this->schemaMapper->toObject($input, $tool->getInputSchema());

        if (!isset($this->languageExecutors[$tool->getLanguage()->value])) {
            throw new ExecutorNotFoundException($tool->getLanguage());
        }

        $executor = $this->languageExecutors[$tool->getLanguage()->value];
        try {
            return $executor->execute($tool, $input);
        } catch (\Throwable $e) {
            return \json_encode(['error' => $e->getMessage()]);
        }
    }

    /**
     * Checks if an executor is registered for the given language.
     */
    public function has(ToolLanguage $language): bool
    {
        return isset($this->executors[$language->value]);
    }
}
