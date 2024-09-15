<?php

declare(strict_types=1);

namespace LLM\Agents\Solution\Metadata;

use LLM\Agents\LLM\Output\FormatterInterface;

/**
 * Instruct an agent to use a specific output formatter.
 */
readonly class OutputFormatter extends Option
{
    public function __construct(FormatterInterface $formatter)
    {
        parent::__construct(
            key: 'output_formatter',
            content: $formatter,
        );
    }
}
