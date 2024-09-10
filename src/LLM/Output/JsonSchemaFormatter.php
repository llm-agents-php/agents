<?php

declare(strict_types=1);

namespace LLM\Agents\LLM\Output;

use LLM\Agents\Tool\SchemaMapperInterface;
use LLM\Agents\LLM\Exception\InvalidArgumentException;

/**
 * Formats the output as a JSON object based on a JSON schema.
 *
 * @template T of object
 */
final class JsonSchemaFormatter implements FormatterInterface
{
    private ?string $jsonSchema = null;
    /** @var class-string<T>|null */
    private ?string $class = null;

    public function __construct(
        private readonly SchemaMapperInterface $schemaMapper,
    ) {}

    /**
     * @param non-empty-string|class-string<T> $jsonSchema
     */
    public function withJsonSchema(string $jsonSchema): self
    {
        $self = clone $this;

        if (\class_exists($jsonSchema)) {
            $this->class = $jsonSchema;
            $jsonSchema = \json_encode($this->schemaMapper->toJsonSchema($jsonSchema));
        } elseif (!\json_validate($jsonSchema)) {
            throw new InvalidArgumentException('Invalid JSON schema provided');
        }

        $self->jsonSchema = $jsonSchema;

        return $this;
    }

    /**
     * @return object|T
     */
    public function format(string|\Stringable $output): mixed
    {
        $output = $this->cleanOutput((string) $output);

        return $this->schemaMapper->toObject($output, $this->class);
    }

    public function getInstruction(): ?string
    {
        if ($this->jsonSchema === null) {
            throw new InvalidArgumentException('You must call withJsonSchema() before using this formatter.');
        }

        return \sprintf(
            <<<'INSTRUCTION'
Answer in JSON using this schema:
%s
INSTRUCTION,
            $this->jsonSchema,
        );
    }

    private function cleanOutput(string $output): string
    {
        $output = \trim($output);
        // Remove code block fences (with optional language name)
        $pattern = '/```[\w]*\n([\s\S]*?)\n```/is';

        // Replace with the content inside the code block
        return \preg_replace($pattern, '$1', $output);
    }
}
