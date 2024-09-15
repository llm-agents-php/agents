<?php

declare(strict_types=1);

namespace LLM\Agents\LLM\Prompt\Chat;

use LLM\Agents\LLM\Response\Response;

/**
 * @internal
 */
final class ToolsCallResultResponse extends Response implements \JsonSerializable
{
    /**
     * @param array<ToolCallResultMessage> $results
     */
    public function __construct(
        public array $results,
    ) {
        parent::__construct('');
    }

    public function jsonSerialize(): array
    {
        return [
            'results' => \array_map(
                static fn(ToolCallResultMessage $result): array => $result->toArray(),
                $this->results,
            ),
        ];
    }
}
