<?php

namespace App\Commands\MediaSync\Traits\SyncFromDatabaseToUploadsDirectory\Traits;

use App\Commands\MediaSync\Traits\SyncFromDatabaseToUploadsDirectory\Traits\Chunk\Exceptions\InvalidChunkValue;
use App\Commands\MediaSync\Traits\SyncFromDatabaseToUploadsDirectory\Traits\Chunk\Exceptions\InvalidOptionsUse;
use App\Commands\MediaSync\Traits\SyncFromDatabaseToUploadsDirectory\Traits\Chunk\Exceptions\StopUploadsProcess;
use App\Commands\MediaSync\Traits\SyncFromDatabaseToUploadsDirectory\Traits\Chunk\Traits\SymfonyOptionsMounter;
use App\Commands\MediaSync\Traits\SyncFromDatabaseToUploadsDirectory\Traits\Chunk\Traits\SymfonyQuestionHelper;

trait Chunk
{
    use SymfonyOptionsMounter;
    use SymfonyQuestionHelper;

    private int $chunk_number = 0;
    private bool $only_once;
    private int $processed_items_count = 0;
    /** @var int|false $items_per_chunk */
    private $items_per_chunk;

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
     * @throws InvalidChunkValue
     */
    private function finishedChunk(): bool
    {
        return $this->processed_items_count === $this->getItemsPerChunk();
    }

    /**
     * @param int|false $items_per_chunk
     * @return int|null
     */
    private function formatChunkValue($items_per_chunk): ?int
    {
        return $items_per_chunk === false ? null : $items_per_chunk;
    }

    /**
     * @return int|null
     * @throws InvalidChunkValue
     */
    private function getItemsPerChunk(): ?int
    {
        if (isset($this->items_per_chunk)) {
            return $this->formatChunkValue($this->items_per_chunk);
        }

        $chunk_option_value = $this->input->getOption(self::OPTION_NAME_CHUNK);
        $chunk_option_value = $chunk_option_value === '' ? null : $chunk_option_value;
        $chunk_option_value = $chunk_option_value ?? self::OPTION_CHUNK_DEFAULT;

        if (is_string($chunk_option_value)) {
            $chunk_option_value = $this->convertChunkStringValueToInteger($chunk_option_value);
        }

        return $this->formatChunkValue($this->items_per_chunk = $chunk_option_value);
    }

    /**
     * @return bool
     * @throws InvalidChunkValue
     */
    private function isChunked(): bool
    {
        if (($is_chunked = $this->getItemsPerChunk() !== null) && $this->chunk_number === 0) {
            $this->chunk_number = 1;
        }

        return $is_chunked;
    }

    private function isOnlyOnce(): bool
    {
        return $this->only_once ?? $this->only_once = $this->input->getOption(self::OPTION_NAME_ONCE);
    }

    /**
     * @return void
     * @throws InvalidChunkValue
     * @throws StopUploadsProcess
     */
    private function resolveFinishedChunk()
    {
        if (!$this->isChunked()) {
            return;
        }

        if (!$this->finishedChunk()) {
            return;
        }

        if ($this->isOnlyOnce() || !$this->askToContinueToNextChunk()) {
            throw new StopUploadsProcess($this->chunk_number);
        }

        $this->chunk_number++;
    }

    /**
     * @return void
     * @throws InvalidOptionsUse
     */
    private function validateOptions()
    {
        if ($this->isOnlyOnce() && !$this->isChunked()) {
            throw new InvalidOptionsUse;
        }
    }
}
