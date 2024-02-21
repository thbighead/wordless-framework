<?php

namespace App\Commands\MediaSync\Traits\SyncFromDatabaseToUploadsDirectory\Traits\Chunk\Exceptions;

use App\Commands\MediaSync;
use Exception;
use Throwable;

class InvalidOptionsUse extends Exception
{
    public function __construct(Throwable $previous = null)
    {
        parent::__construct(
            'Used --'
            . MediaSync::OPTION_NAME_ONCE
            . ' option without --'
            . MediaSync::OPTION_NAME_CHUNK
            . ' option.',
            0,
            $previous
        );
    }
}
