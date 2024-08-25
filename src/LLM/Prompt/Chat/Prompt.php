<?php

declare(strict_types=1);

namespace LLM\Agents\LLM\Prompt\Chat;

use LLM\Agents\LLM\Exception\PromptException;
use LLM\Agents\LLM\Prompt\FormatterInterface;
use LLM\Agents\LLM\Prompt\FString;
use LLM\Agents\LLM\Prompt\MessageInterface;
use LLM\Agents\LLM\Prompt\SerializableInterface;

final class Prompt implements PromptInterface
{
    public function __construct(
        /** @var MessageInterface[] */
        protected array $messages = [],
        protected array $variables = [],
    ) {
        foreach ($this->messages as $message) {
            if (!$message instanceof MessageInterface) {
                throw new PromptException(\sprintf('Messages must be of type %s.', MessageInterface::class));
            }
        }
    }

    public function withValues(array $values): self
    {
        $prompt = clone $this;
        $prompt->variables = \array_merge($this->variables, $values);
        return $prompt;
    }

    public function withAddedMessage(MessageInterface $message): self
    {
        $prompt = clone $this;
        $prompt->messages[] = $message;
        return $prompt;
    }

    public function getMessages(): array
    {
        return $this->messages;
    }

    public function format(array $variables = []): array
    {
        $variables = \array_merge($this->variables, $variables);

        $result = [];
        foreach ($this->messages as $message) {
            if ($message instanceof MessagePrompt) {
                $msg = $message->toChatMessage($variables);
                if ($msg !== null) {
                    $result[] = $msg;
                }

                continue;
            }

            $result[] = $message;
        }

        return $result;
    }

    public function __toString(): string
    {
        return \json_encode($this->format());
    }

    public function count(): int
    {
        return \count($this->messages);
    }

    public function toArray(): array
    {
        $result = [
            'messages' => \array_map(
                static fn(SerializableInterface $message) => [
                    'class' => $message::class,
                    'data' => $message->toArray(),
                ],
                $this->messages,
            ),
        ];

        if (!empty($this->variables)) {
            $result['variables'] = $this->variables;
        }

        return $result;
    }

    public static function fromArray(
        array $data,
        FormatterInterface $formatter = new FString(),
    ): self {
        if ($data === []) {
            return new self();
        }

        $messages = \array_map(
            static fn(array $message) => $message['class']::fromArray($message['data'], $formatter),
            $data['messages'],
        );

        return new self($messages, $data['variables'] ?? []);
    }
}