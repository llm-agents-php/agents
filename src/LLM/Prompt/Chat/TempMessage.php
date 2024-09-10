<?php

declare(strict_types=1);

namespace LLM\Agents\LLM\Prompt\Chat;

/**
 * This class represents a temporary message that is not saved in the chat history.
 */
readonly class TempMessage extends ChatMessage implements TempMessageInterface
{

}
