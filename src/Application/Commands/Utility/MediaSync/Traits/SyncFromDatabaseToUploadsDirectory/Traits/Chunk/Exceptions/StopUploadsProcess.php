<?php

namespace App\Commands\MediaSync\Traits\SyncFromDatabaseToUploadsDirectory\Traits\Chunk\Exceptions;

use Exception;
use Throwable;

class StopUploadsProcess extends Exception
{
    private int $chunk_number;

    public function __construct(int $chunk_number, Throwable $previous = null)
    {
        $this->chunk_number = $chunk_number;

        parent::__construct("Process stoped in chunk $this->chunk_number", 0, $previous);
    }

    public function getChunkNumber(): int
    {
        return $this->chunk_number;
    }
}
