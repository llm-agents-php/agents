<?php

declare(strict_types=1);

namespace LLM\Agents\Tool;

use LLM\Agents\Tool\Exception\ExecutorNotFoundException;
use LLM\Agents\Tool\Exception\UnsupportedToolExecutionException;

final class ToolExecutor
{
    /**
     * @var array<ExecutorInterface>
     */
    private array $executors = [];

    public function __construct(
        private readonly ToolRepositoryInterface $tools,
        private readonly SchemaMapperInterface $schemaMapper,
    ) {}

    public function register(ToolLanguage $language, ExecutorInterface $executor): void
    {
        $this->executors[$language->value] = $executor;
    }

    /**
     * @throws ExecutorNotFoundException
     * @throws UnsupportedToolExecutionException
     */
    public function execute(string $tool, string $input): string|\Stringable
    {
        $tool = $this->tools->get($tool);

        // In some cases, the input schema can be a class name or a JSON schema string.
        // For example, the input schema can be a class name when the tool is a PHP tool.
        // Or it can be a JSON schema string when the tool is a Python tool stored in the database.
        // So we need to handle both cases.
        if (\class_exists($tool->getInputSchema())) {
            $input = $this->schemaMapper->toObject($input, $tool->getInputSchema());
        } else {
            $input = \json_decode($input);
        }

        if ($tool->getLanguage() === ToolLanguage::PHP) {
            try {
                return $tool->execute($input);
            } catch (\Throwable $e) {
                return \json_encode([
                    'error' => $e->getMessage(),
                ]);
            }
        }

        if (!$this->has($tool->getLanguage())) {
            throw new ExecutorNotFoundException($tool->getLanguage());
        }

        $executor = $this->executors[$tool->getLanguage()->value];

        if ($tool instanceof ExecutorAwareInterface) {
            try {
                return $tool->setExecutor($executor)->execute($input);
            } catch (\Throwable $e) {
                return \json_encode([
                    'error' => $e->getMessage(),
                ]);
            }
        }

        throw new UnsupportedToolExecutionException($tool->getName());
    }

    public function has(ToolLanguage $language): bool
    {
        return isset($this->executors[$language->value]);
    }
}
