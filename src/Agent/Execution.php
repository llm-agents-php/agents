<?php

declare(strict_types=1);

namespace LLM\Agents\Agent;

use LLM\Agents\LLM\Prompt\Chat\PromptInterface;
use LLM\Agents\LLM\Response\Response;

final readonly class Execution
{
    public function __construct(
        public Response $result,
        public PromptInterface $prompt,
    ) {}
}
