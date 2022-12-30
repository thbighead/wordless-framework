<?php

namespace Wordless\Exceptions;

use Exception;
use Throwable;
use Wordless\Helpers\GetType;

class CannotInitializeComposerIo extends Exception
{
    public function __construct($event, Throwable $previous = null)
    {
        parent::__construct(
            'Failed to load Composer IO Interface from object of type ' . GetType::of($event),
            0,
            $previous
        );
    }
}
