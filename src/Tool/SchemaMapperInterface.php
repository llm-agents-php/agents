<?php

declare(strict_types=1);

namespace LLM\Agents\Tool;

/**
 * Provides functionality to convert between JSON schemas and PHP objects.
 */
interface SchemaMapperInterface
{
    /**
     * Convert a PHP class to a JSON schema.
     *
     * @param class-string $class The fully qualified class name to convert.
     * @return array The JSON schema representation of the class.
     */
    public function toJsonSchema(string $class): array;

    /**
     * Convert a JSON string to a PHP object.
     *
     * @template T of object
     * @param string $json The JSON string to convert.
     * @param class-string<T>|string|null $class The target class to map the JSON to. If null, returns a stdClass.
     *
     * @return T The resulting PHP object.
     */
    public function toObject(string $json, ?string $class = null): object;
}
