<?php

declare(strict_types=1);

namespace LLM\Agents\Embeddings;

final class DocumentSplitter
{
    public function split(
        Document $document,
        int $maxLength = 1000,
        string $separator = ' ',
        int $wordOverlap = 0,
    ): array {
        $content = $this->filterText($document->content);
        $length = $document->length;

        if ($content === '') {
            return [$document];
        }

        if ($separator === '') {
            return [];
        }

        if ($length <= $maxLength) {
            return [$document];
        }

        $words = \explode($separator, $content);

        return \array_map(
            static fn(string $chunk): Document => new Document(
                content: $chunk,
                source: $document->source,
            ),
            $this->createChunks($words, $maxLength, $separator, $wordOverlap),
        );
    }

    private function filterText(string $text): string
    {
        // Remove special characters (except for basic punctuation)
        $text = \preg_replace('/[^a-zA-Z0-9\s,.!?]/', '', $text);

        // Remove extra spaces
        return \trim(\preg_replace('/\s+/', ' ', $text));
    }

    /**
     * @param array<string> $words
     * @return array<non-empty-string>
     */
    private function createChunks(array $words, int $maxLength, string $separator, int $wordOverlap): array
    {
        $chunks = [];
        $chunk = '';

        $i = 0;
        while ($i < count($words)) {
            // If adding the next word would exceed the chunk size, add the current chunk and start a new one
            if (\strlen($chunk . $separator . $words[$i]) > $maxLength) {
                $chunks[] = \trim($chunk);
                // Set the starting point of the next chunk considering word overlap
                $start = \max(0, $i - $wordOverlap);
                $chunk = \implode($separator, \array_slice($words, $start, $wordOverlap));
            }

            $chunk .= $separator . $words[$i];
            $i++;
        }

        // Add the last chunk
        if (!empty($chunk)) {
            $chunks[] = \trim($chunk);
        }

        return $chunks;
    }
}
