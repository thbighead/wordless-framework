<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Utility\MediaSync\Traits\SyncFromDatabaseToUploadsDirectory\Traits;

use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Exception\RuntimeException;
use Wordless\Application\Commands\Utility\MediaSync\Traits\SyncFromDatabaseToUploadsDirectory\Traits\Chunk\Exceptions\FailedToResolveFinishedChunk;
use Wordless\Application\Commands\Utility\MediaSync\Traits\SyncFromDatabaseToUploadsDirectory\Traits\Chunk\Exceptions\InvalidChunkValue;
use Wordless\Application\Commands\Utility\MediaSync\Traits\SyncFromDatabaseToUploadsDirectory\Traits\Chunk\Exceptions\InvalidOptionsUsage;
use Wordless\Application\Commands\Utility\MediaSync\Traits\SyncFromDatabaseToUploadsDirectory\Traits\Chunk\Exceptions\StopUploadsProcess;
use Wordless\Application\Commands\Utility\MediaSync\Traits\SyncFromDatabaseToUploadsDirectory\Traits\Chunk\Traits\OptionsMounter;
use Wordless\Application\Commands\Utility\MediaSync\Traits\SyncFromDatabaseToUploadsDirectory\Traits\Chunk\Traits\OptionsMounter\DTO\ChunkOptionDTO;
use Wordless\Application\Commands\Utility\MediaSync\Traits\SyncFromDatabaseToUploadsDirectory\Traits\Chunk\Traits\OptionsMounter\DTO\OnceOptionDTO;
use Wordless\Application\Commands\Utility\MediaSync\Traits\SyncFromDatabaseToUploadsDirectory\Traits\Chunk\Traits\SymfonyQuestionHelper;
use Wordless\Infrastructure\ConsoleCommand\Traits\Questions\Exceptions\GetQuestionHelperException;

trait Chunk
{
    use OptionsMounter;
    use SymfonyQuestionHelper;

    private int $chunk_number = 0;
    private int|false $items_per_chunk;
    private bool $only_once;
    private int $processed_items_count = 0;

    /**
     * @param string $value
     * @return int
     * @throws InvalidChunkValue
     */
    private function convertChunkStringValueToInteger(string $value): int
    {
        if (!is_numeric($value)) {
            throw new InvalidChunkValue($value);
        }

        if (($value_as_integer = (int)$value) <= 0) {
            throw new InvalidChunkValue($value);
        }

        return $value_as_integer;
    }

    /**
     * @return bool
     * @throws InvalidArgumentException
     * @throws InvalidChunkValue
     */
    private function finishedChunk(): bool
    {
        return $this->processed_items_count === $this->getItemsPerChunk();
    }

    private function formatChunkValue(int|false $items_per_chunk): ?int
    {
        return $items_per_chunk === false ? null : $items_per_chunk;
    }

    /**
     * @return int|null
     * @throws InvalidChunkValue
     * @throws InvalidArgumentException
     */
    private function getItemsPerChunk(): ?int
    {
        if (isset($this->items_per_chunk)) {
            return $this->formatChunkValue($this->items_per_chunk);
        }

        $chunk_option_value = $this->input->getOption(ChunkOptionDTO::OPTION_NAME);
        $chunk_option_value = $chunk_option_value === '' ? null : $chunk_option_value;
        $chunk_option_value = $chunk_option_value ?? self::OPTION_CHUNK_DEFAULT;

        if (is_string($chunk_option_value)) {
            $chunk_option_value = $this->convertChunkStringValueToInteger($chunk_option_value);
        }

        return $this->formatChunkValue($this->items_per_chunk = $chunk_option_value);
    }

    /**
     * @return bool
     * @throws InvalidArgumentException
     * @throws InvalidChunkValue
     */
    private function isChunked(): bool
    {
        if (($is_chunked = $this->getItemsPerChunk() !== null) && $this->chunk_number === 0) {
            $this->chunk_number = 1;
        }

        return $is_chunked;
    }

    /**
     * @return bool
     * @throws InvalidArgumentException
     */
    private function isOnlyOnce(): bool
    {
        return $this->only_once ?? $this->only_once = $this->input->getOption(OnceOptionDTO::OPTION_NAME);
    }

    /**
     * @return void
     * @throws FailedToResolveFinishedChunk
     * @throws StopUploadsProcess
     */
    private function resolveFinishedChunk(): void
    {
        try {
            if (!$this->isChunked()) {
                return;
            }

            if (!$this->finishedChunk()) {
                return;
            }

            if ($this->isOnlyOnce() || !$this->askToContinueToNextChunk()) {
                throw new StopUploadsProcess($this->chunk_number);
            }
        } catch (GetQuestionHelperException|InvalidArgumentException|InvalidChunkValue|RuntimeException $exception) {
            throw new FailedToResolveFinishedChunk($exception);
        }

        $this->chunk_number++;
    }

    /**
     * @return void
     * @throws InvalidArgumentException
     * @throws InvalidChunkValue
     * @throws InvalidOptionsUsage
     */
    private function validateOptions(): void
    {
        if ($this->isOnlyOnce() && !$this->isChunked()) {
            throw new InvalidOptionsUsage;
        }
    }
}
