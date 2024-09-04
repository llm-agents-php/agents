<p align="center">
    <br>
    <a href="https://github.com/llm-agents-php" target="_blank">
        <picture>
            <source media="(prefers-color-scheme: dark)" srcset="https://raw.githubusercontent.com/llm-agents-php/.github/master/assets/logo.png">
            <img width="200" src="https://raw.githubusercontent.com/llm-agents-php/.github/master/assets/logo.png" alt="LLM Agents Logo">
        </picture>
    </a>
    <br>
</p>

# LLM Agents PHP SDK

LLM Agents is a powerful PHP library for building and managing Language Model (LLM) based agents. This package provides
a flexible and extensible framework for creating autonomous agents that can perform complex tasks, make decisions, and
interact with various tools and APIs.

> There is an article on Medium where I explaind what is LLM agents: [A PHP dev’s dream: An AI home that really gets you
](https://butschster.medium.com/a-php-devs-dream-an-ai-home-that-really-gets-you-dd97ae2ca0b0)

[![PHP](https://img.shields.io/packagist/php-v/llm-agents/agents.svg?style=flat-square)](https://packagist.org/packages/llm-agents/agents)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/llm-agents/agents.svg?style=flat-square)](https://packagist.org/packages/llm-agents/agents)
[![Total Downloads](https://img.shields.io/packagist/dt/llm-agents/agents.svg?style=flat-square)](https://packagist.org/packages/llm-agents/agents)

> For a complete example with sample agents and a CLI interface to interact with them, check out our sample application
> repository https://github.com/llm-agents-php/sample-app. This sample app demonstrates practical implementations and
> usage patterns of the LLM Agents library.

The package does not include any specific LLM implementation. Instead, it provides a framework for creating agents that
can interact with any LLM service or API.

## Features

- **Flexible Agent Creation**: Easily create and configure LLM-based agents with customizable behaviors and
  capabilities.
- **Tool Integration**: Seamlessly integrate various tools and APIs for agent use, enhancing their problem-solving
  abilities.
- **Memory Management**: Built-in support for agent memory, allowing agents to retain and recall information from
  previous interactions.
- **Prompt Management**: Efficient handling of prompts and instructions for guiding agent behavior.
- **Extensible Architecture**: Designed with extensibility in mind, allowing easy addition of new agent types, tools,
  and capabilities.
- **Multi-Agent Support**: Create systems with multiple interacting agents for complex problem-solving scenarios.

## Installation

You can install the LLM Agents package via Composer:

```bash
composer require llm-agents/agents
```

## Usage

### Creating an Agent

To create an agent, you'll need to define its behavior, tools, and configuration. Here's a basic example:

```php
use LLM\Agents\Agent\AgentAggregate;
use LLM\Agents\Agent\Agent;
use LLM\Agents\Solution\Model;
use LLM\Agents\Solution\ToolLink;
use LLM\Agents\Solution\MetadataType;
use LLM\Agents\Solution\SolutionMetadata;

class SiteStatusCheckerAgent extends AgentAggregate
{
    public const NAME = 'site_status_checker';

    public static function create(): self
    {
        $agent = new Agent(
            key: self::NAME,
            name: 'Site Status Checker',
            description: 'This agent checks the online status of websites.',
            instruction: 'You are a website status checking assistant. Your goal is to help users determine if a website is online. Use the provided tool to check site availability. Give clear, concise responses about a site\'s status.',
        );

        $aggregate = new self($agent);

        $aggregate->addMetadata(
            new SolutionMetadata(
                type: MetadataType::Memory,
                key: 'check_availability',
                content: 'Always check the site\'s availability using the provided tool.',
            ),
            new SolutionMetadata(
                type: MetadataType::Configuration,
                key: 'max_tokens',
                content: 500,
            )
        );

        $model = new Model(model: 'gpt-4o-mini');
        $aggregate->addAssociation($model);

        $aggregate->addAssociation(new ToolLink(name: CheckSiteAvailabilityTool::NAME));

        return $aggregate;
    }
}
```

### Implementing a Tool

Now, let's implement the tool used by this agent:

```php
use LLM\Agents\Tool\Tool;
use LLM\Agents\Tool\ToolLanguage;

class CheckSiteAvailabilityTool extends Tool
{
    public const NAME = 'check_site_availability';

    public function __construct()
    {
        parent::__construct(
            name: self::NAME,
            inputSchema: CheckSiteAvailabilityInput::class,
            description: 'This tool checks if a given URL is accessible and returns its HTTP status code and response time.',
        );
    }

    public function getLanguage(): ToolLanguage
    {
        return ToolLanguage::PHP;
    }

    public function execute(object $input): string
    {
        $ch = curl_init($input->url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true,
            CURLOPT_NOBODY => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
        ]);

        $startTime = microtime(true);
        $response = curl_exec($ch);
        $endTime = microtime(true);

        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $responseTime = round(($endTime - $startTime) * 1000, 2);

        curl_close($ch);

        $isOnline = $statusCode >= 200 && $statusCode < 400;

        return json_encode([
            'status_code' => $statusCode,
            'response_time_ms' => $responseTime,
            'is_online' => $isOnline,
        ]);
    }
}
```

And the input schema for the tool:

```php
use Spiral\JsonSchemaGenerator\Attribute\Field;

class CheckSiteAvailabilityInput
{
    public function __construct(
        #[Field(title: 'URL', description: 'The full URL of the website to check')]
        public readonly string $url,
    ) {}
}
```

### Linking Agents

LLM Agents supports creating complex systems by linking multiple agents together. This allows you to build hierarchical
or collaborative agent networks. Here's how you can link one agent to another:

#### Creating an Agent Link

To link one agent to another, you use the `AgentLink` class. Here's an example of how to modify our
`SiteStatusCheckerAgent` to include a link to another agent:

```php
use LLM\Agents\Solution\AgentLink;

class SiteStatusCheckerAgent extends AgentAggregate
{
    public const NAME = 'site_status_checker';

    public static function create(): self
    {
        // ... [previous agent setup code] ...

        // Link to another agent
        $aggregate->addAssociation(
            new AgentLink(
                name: 'network_diagnostics_agent',
                outputSchema: NetworkDiagnosticsOutput::class,
            ),
        );

        return $aggregate;
    }
}
```

In this example, we're linking a `network_diagnostics_agent`. The `outputSchema` parameter specifies the expected output
format from the linked agent. The output schema is used to standardize the data format that should be returned by the
linked agent.

#### Using a Linked Agent

We don't provide an implementation for the linked agent here, but you can use the linked agent in your agent's
execution.

Here's an example of how you might call the linked agent:

```php
use LLM\Agents\Tool\Tool;
use LLM\Agents\Agent\AgentExecutor;
use LLM\Agents\LLM\Prompt\Chat\ToolCallResultMessage;
use LLM\Agents\LLM\Response\ToolCalledResponse;
use LLM\Agents\Tool\ToolExecutor;
use LLM\Agents\Tool\ToolLanguage;

/**
 * @extends PhpTool<AskAgentInput>
 */
final class AskAgentTool extends Tool
{
    public const NAME = 'ask_agent';

    public function __construct(
        private readonly AgentExecutor $executor,
        private readonly ToolExecutor $toolExecutor,
    ) {
        parent::__construct(
            name: self::NAME,
            inputSchema: AskAgentInput::class,
            description: 'Ask an agent with given name to execute a task.',
        );
    }

    public function getLanguage(): ToolLanguage
    {
        return ToolLanguage::PHP;
    }

    public function execute(object $input): string|\Stringable
    {
        $prompt = \sprintf(
            <<<'PROMPT'
%s
Important rules:
- Think before responding to the user.
- Don not markup the content. Only JSON is allowed.
- Don't write anything except the answer using JSON schema.
- Answer in JSON using this schema:
%s
PROMPT
            ,
            $input->question,
            $input->outputSchema,
        );

        while (true) {
            $execution = $this->executor->execute($input->name, $prompt);
            $result = $execution->result;
            $prompt = $execution->prompt;

            if ($result instanceof ToolCalledResponse) {
                foreach ($result->tools as $tool) {
                    $functionResult = $this->toolExecutor->execute($tool->name, $tool->arguments);

                    $prompt = $prompt->withAddedMessage(
                        new ToolCallResultMessage(
                            id: $tool->id,
                            content: [$functionResult],
                        ),
                    );
                }

                continue;
            }

            break;
        }

        return \json_encode($result->content);
    }
}
```

And the input schema for the tool:

```php
use Spiral\JsonSchemaGenerator\Attribute\Field;

final class AskAgentInput
{
    public function __construct(
        #[Field(title: 'Agent Name', description: 'The name of the agent to ask.')]
        public string $name,
        #[Field(title: 'Question', description: 'The question to ask the agent.')]
        public string $question,
        #[Field(title: 'Output Schema', description: 'The schema of the output.')]
        public string $outputSchema,
    ) {}
}
```

And just add the tool to the agent that has linked agents. When the agent is executed, it will call the linked agent
if it decides to do so.

### Executing an Agent

To execute an agent, you'll use the `AgentExecutor` class:

```php
use LLM\Agents\Agent\AgentExecutor;
use LLM\Agents\LLM\Prompt\Chat\Prompt;
use LLM\Agents\Tool\ToolExecutor;
use LLM\Agents\LLM\Prompt\Chat\ToolCallResultMessage;
use LLM\Agents\LLM\Response\ChatResponse;
use LLM\Agents\LLM\Response\ToolCall;

final readonly class AgentRunner
{
    public function __construct(
        private AgentExecutor $executor,
        private ToolExecutor $toolExecutor,
    ) {}

    public function run(string $url): string
    {
        $prompt = new Prompt([
            Prompt\MessagePrompt::user("Check the status of $url"),
        ]);

        $finished = false;
        while (true) {
            $execution = $this->executor->execute(
                agent: SiteStatusCheckerAgent::NAME,
                prompt: $prompt,
            );
        
            $result = $execution->result;
            $prompt = $execution->prompt;

            if ($result instanceof ToolCalledResponse) {
                // First, call all tools.
                $toolsResponse = [];
                foreach ($result->tools as $tool) {
                    $toolsResponse[] = $this->callTool($session, $tool);
                }

                // Then add the tools responses to the prompt.
                foreach ($toolsResponse as $toolResponse) {
                    $prompt = $prompt->withAddedMessage($toolResponse);
                }
            } elseif ($result instanceof ChatResponse) {
                $finished = true;
            }

            if ($finished) {
                break;
            }
        }

        return (string)$execution->result->content;
    }

    private function callTool(ToolCall $tool): ToolCallResultMessage
    {
        $functionResult = $this->toolExecutor->execute($tool->name, $tool->arguments);

        return new ToolCallResultMessage(
            id: $tool->id,
            content: [$functionResult],
        );
    }
}

// Usage
$agentRunner = new AgentRunner(/* ... */);

$result = $agentRunner->run('Check the status of https://example.com');

echo $result;
```

This example demonstrates how to create a simple agent that can perform a specific task using a custom tool.

### Agent Memory and Prompts

Agents can use memory and predefined prompts to guide their behavior:

```php
use LLM\Agents\Solution\SolutionMetadata;
use LLM\Agents\Solution\MetadataType;

// In your agent creation method:
$aggregate->addMetadata(
    new SolutionMetadata(
        type: MetadataType::Memory,
        key: 'user_preference',
        content: 'The user prefers concise answers.',
    ),
    
    new SolutionMetadata(
        type: MetadataType::Prompt,
        key: 'check_google',
        content: 'Check the status of google.com.',
    ),
    
    new SolutionMetadata(
        type: MetadataType::Prompt,
        key: 'check_yahoo',
        content: 'Check the status of yahoo.com.',
    ),
    
    //...
);
```

## Implementing Required Interfaces

To use the LLM Agents package, you'll need to implement the required interfaces in your project.

### LLMInterface

It serves as a bridge between your application and LLM you're using, such as OpenAI, Claude, etc.

```php
use LLM\Agents\LLM\ContextInterface;
use LLM\Agents\LLM\LLMInterface;
use LLM\Agents\LLM\OptionsInterface;
use LLM\Agents\LLM\Prompt\Chat\MessagePrompt;
use LLM\Agents\LLM\Prompt\Chat\PromptInterface as ChatPromptInterface;
use LLM\Agents\LLM\Prompt\PromptInterface;
use LLM\Agents\LLM\Prompt\Tool;
use LLM\Agents\LLM\Response\Response;
use OpenAI\Client;

final readonly class OpenAILLM implements LLMInterface
{
    public function __construct(
        private Client $client,
        private MessageMapper $messageMapper,
        private StreamResponseParser $streamParser,
    ) {}

    public function generate(
        ContextInterface $context,
        PromptInterface $prompt,
        OptionsInterface $options,
    ): Response {
        $request = $this->buildOptions($options);

        $messages = $prompt instanceof ChatPromptInterface
            ? $prompt->format()
            : [MessagePrompt::user($prompt)->toChatMessage()];

        $request['messages'] = array_map(
            fn($message) => $this->messageMapper->map($message),
            $messages
        );

        if ($options->has('tools')) {
            $request['tools'] = array_values(array_map(
                fn(Tool $tool): array => $this->messageMapper->map($tool),
                $options->get('tools')
            ));
        }

        $stream = $this->client->chat()->createStreamed($request);

        return $this->streamParser->parse($stream);
    }

    private function buildOptions(OptionsInterface $options): array
    {
        $defaultOptions = [
            'temperature' => 0.8,
            'max_tokens' => 120,
            'model' => null,
            // Add other default options as needed
        ];

        $result = array_intersect_key($options->getIterator()->getArrayCopy(), $defaultOptions);
        $result += array_diff_key($defaultOptions, $result);

        if (!isset($result['model'])) {
            throw new \InvalidArgumentException('Model is required');
        }

        return array_filter($result, fn($value) => $value !== null);
    }
}
```

Here is an example of `MessageMapper` that converts messages to the format required by the LLM API:

```php
use LLM\Agents\LLM\Prompt\Chat\ChatMessage;
use LLM\Agents\LLM\Prompt\Chat\Role;
use LLM\Agents\LLM\Prompt\Chat\ToolCalledPrompt;
use LLM\Agents\LLM\Prompt\Chat\ToolCallResultMessage;
use LLM\Agents\LLM\Prompt\Tool;
use LLM\Agents\LLM\Response\ToolCall;

final readonly class MessageMapper
{
    public function map(object $message): array
    {
        if ($message instanceof ChatMessage) {
            return [
                'content' => $message->content,
                'role' => $message->role->value,
            ];
        }

        if ($message instanceof ToolCallResultMessage) {
            return [
                'content' => \is_array($message->content) ? \json_encode($message->content) : $message->content,
                'tool_call_id' => $message->id,
                'role' => $message->role->value,
            ];
        }

        if ($message instanceof ToolCalledPrompt) {
            return [
                'content' => null,
                'role' => Role::Assistant->value,
                'tool_calls' => \array_map(
                    static fn(ToolCall $tool): array => [
                        'id' => $tool->id,
                        'type' => 'function',
                        'function' => [
                            'name' => $tool->name,
                            'arguments' => $tool->arguments,
                        ],
                    ],
                    $message->tools,
                ),
            ];
        }

        if ($message instanceof Tool) {
            return [
                'type' => 'function',
                'function' => [
                    'name' => $message->name,
                    'description' => $message->description,
                    'parameters' => [
                            'type' => 'object',
                            'additionalProperties' => $message->additionalProperties,
                        ] + $message->parameters,
                    'strict' => $message->strict,
                ],
            ];
        }

        if ($message instanceof \JsonSerializable) {
            return $message->jsonSerialize();
        }

        throw new \InvalidArgumentException('Invalid message type');
    }
}
```

### AgentPromptGeneratorInterface

It plays a vital role in preparing the context and instructions for an agent before it processes a user's request. It
ensures that the agent has all necessary information, including its own instructions, memory, associated agents, and any
relevant session context.

- System message with the agent's instruction and important rules.
- System message with the agent's memory (experiences).
- System message about associated agents (if any).
- System message with session context (if provided).
- User message with the actual prompt.

You can customize the prompt generation logic to suit your specific requirements.

Here's an example implementation of the `LLM\Agents\LLM\AgentPromptGeneratorInterface`:

```php
use LLM\Agents\Agent\AgentInterface;
use LLM\Agents\Agent\AgentRepositoryInterface;
use LLM\Agents\LLM\AgentPromptGeneratorInterface;
use LLM\Agents\LLM\Prompt\Chat\ChatMessage;
use LLM\Agents\LLM\Prompt\Chat\MessagePrompt;
use LLM\Agents\LLM\Prompt\Chat\Prompt;
use LLM\Agents\LLM\Prompt\Chat\PromptInterface;
use LLM\Agents\LLM\Prompt\Chat\Role;
use LLM\Agents\Solution\AgentLink;
use LLM\Agents\Solution\SolutionMetadata;
use Spiral\JsonSchemaGenerator\Generator;

final readonly class AgentPromptGenerator implements AgentPromptGeneratorInterface
{
    public function __construct(
        private AgentRepositoryInterface $agents,
        private Generator $schemaGenerator,
    ) {}

    public function generate(
        AgentInterface $agent,
        string|\Stringable $prompt,
        ?array $sessionContext = null,
    ): PromptInterface {
        $messages = [
            // Top instruction
            MessagePrompt::system(
                prompt: \sprintf(
                    <<<'PROMPT'
{prompt}
Important rules:
- always response in markdown format
- think before responding to user
PROMPT,
                ),
                values: ['prompt' => $agent->getInstruction()],
            ),

            // Agent memory
            MessagePrompt::system(
                prompt: 'Instructions about your experiences, follow them: {memory}',
                values: [
                    'memory' => \implode(
                        "\n",
                        \array_map(
                            static fn(SolutionMetadata $metadata) => $metadata->content,
                            $agent->getMemory(),
                        ),
                    ),
                ],
            ),
        ];

        $associatedAgents = \array_map(
            fn(AgentLink $agent): array => [
                'agent' => $this->agents->get($agent->getName()),
                'output_schema' => \json_encode($this->schemaGenerator->generate($agent->outputSchema)),
            ],
            $agent->getAgents(),
        );


        if (\count($associatedAgents) > 0) {
            $messages[] = MessagePrompt::system(
                prompt: <<<'PROMPT'
There are agents {associated_agents} associated with you. You can ask them for help if you need it.
Use the `ask_agent` tool and provide the agent key.
Always follow rules:
- Don't make up the agent key. Use only the ones from the provided list.
PROMPT,
                values: [
                    'associated_agents' => \implode(
                        PHP_EOL,
                        \array_map(
                            static fn(array $agent): string => \json_encode([
                                'key' => $agent['agent']->getKey(),
                                'description' => $agent['agent']->getDescription(),
                                'output_schema' => $agent['output_schema'],
                            ]),
                            $associatedAgents,
                        ),
                    ),
                ],
            );
        }

        if ($sessionContext !== null) {
            $messages[] = MessagePrompt::system(
                prompt: 'Session context: {active_context}',
                values: ['active_context' => \json_encode($sessionContext)],
            );
        }

        // User prompt
        $messages[] = new ChatMessage(
            content: $prompt,
            role: Role::User,
        );

        return new Prompt(
            messages: $messages,
        );
    }
}
```

### SchemaMapperInterface

This class is responsible for handling conversions between JSON schemas and PHP objects.

Here's an example implementation of the `LLM\Agents\Tool\SchemaMapperInterface`:

```php
use CuyZ\Valinor\Mapper\TreeMapper;
use LLM\Agents\Tool\SchemaMapperInterface;
use Spiral\JsonSchemaGenerator\Generator as JsonSchemaGenerator;

final readonly class SchemaMapper implements SchemaMapperInterface
{
    public function __construct(
        private JsonSchemaGenerator $generator,
        private TreeMapper $mapper,
    ) {}

    public function toJsonSchema(string $class): array
    {
        if (json_validate($class)) {
            return json_decode($class, associative: true);
        }

        if (class_exists($class)) {
            return $this->generator->generate($class)->jsonSerialize();
        }

        throw new \InvalidArgumentException("Invalid class or JSON schema provided: $class");
    }

    public function toObject(string $json, ?string $class = null): object
    {
        if ($class === null) {
            return json_decode($json, associative: false);
        }

        return $this->mapper->map($class, json_decode($json, associative: true));
    }
}
```

### ContextFactoryInterface

It provides a clean way to pass execution-specific data through the system without tightly coupling components or overly
complicating method signatures.

```php
use LLM\Agents\LLM\ContextFactoryInterface;
use LLM\Agents\LLM\ContextInterface;

final class ContextFactory implements ContextFactoryInterface
{
    public function create(): ContextInterface
    {
        return new class implements ContextInterface {
            // Implement any necessary methods or properties for your context
        };
    }
}
```

### OptionsFactoryInterface

The options is a simple key-value store that allows you to store and retrieve configuration options that can be passed
to LLM clients and other components. For example, you can pass a model name, max tokens, and other configuration options
to an LLM client.

```php
use LLM\Agents\LLM\OptionsFactoryInterface;
use LLM\Agents\LLM\OptionsInterface;

final class OptionsFactory implements OptionsFactoryInterface
{
    public function create(): OptionsInterface
    {
        return new class implements OptionsInterface {
            private array $options = [];

            public function has(string $option): bool
            {
                return isset($this->options[$option]);
            }

            public function get(string $option, mixed $default = null): mixed
            {
                return $this->options[$option] ?? $default;
            }

            public function with(string $option, mixed $value): static
            {
                $clone = clone $this;
                $clone->options[$option] = $value;
                return $clone;
            }

            public function getIterator(): \Traversable
            {
                return new \ArrayIterator($this->options);
            }
        };
    }
}
```

## Architecture

The LLM Agents package is built around several key components:

- **AgentInterface**: Defines the contract for all agents.
- **AgentAggregate**: Implements AgentInterface and aggregates an Agent instance with other Solution objects.
- **Agent**: Represents a single agent with its key, name, description, and instruction.
- **Solution**: Abstract base class for various components like Model and ToolLink.
- **AgentExecutor**: Responsible for executing agents and managing their interactions.
- **Tool**: Represents a capability that an agent can use to perform tasks.

For a visual representation of the architecture, refer to the class diagram in the documentation.

### Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

### License

LLM Agents is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
