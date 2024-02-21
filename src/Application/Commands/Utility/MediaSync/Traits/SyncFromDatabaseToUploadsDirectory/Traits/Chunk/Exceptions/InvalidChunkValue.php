<?php

namespace App\Commands\MediaSync\Traits\SyncFromDatabaseToUploadsDirectory\Traits\Chunk\Exceptions;

use Exception;
use Throwable;

class InvalidChunkValue extends Exception
{
    private string $invalid_value;

    public function __construct(string $invalid_value, Throwable $previous = null)
    {
        $this->invalid_value = $invalid_value;

        parent::__construct(
            "The value '$this->invalid_value' results in a non-integer or less than or equals to zero, which is invalid",
            0,
            $previous
        );
    }

    public function getInvalidValue(): string
    {
        return $this->invalid_value;
    }
}
