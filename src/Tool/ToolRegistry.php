<?php

declare(strict_types=1);

namespace LLM\Agents\Tool;

use LLM\Agents\Tool\Exception\ToolNotFoundException;

final class ToolRegistry implements ToolRegistryInterface, ToolRepositoryInterface
{
    /**
     * @var array<ToolInterface>
     */
    private array $tools = [];

    public function register(ToolInterface ...$tools): void
    {
        foreach ($tools as $tool) {
            $this->tools[$tool->getName()] = $tool;
        }
    }

    public function get(string $name): ToolInterface
    {
        if ($this->has($name)) {
            return $this->tools[$name];
        }

        throw new ToolNotFoundException($name);
    }

    public function has(string $name): bool
    {
        return isset($this->tools[$name]);
    }

    public function all(): iterable
    {
        return $this->tools;
    }
}
