<?php

declare(strict_types=1);

namespace LLM\Agents\Embeddings;

use LLM\Agents\Solution\ContextSourceLink;

interface HasLinkedContextSourcesInterface
{
    /**
     * Get the sources of the context storage.
     *
     * @return array<ContextSourceLink>
     */
    public function getContextSources(): array;
}
