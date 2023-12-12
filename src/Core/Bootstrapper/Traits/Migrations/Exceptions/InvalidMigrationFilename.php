<?php

namespace Wordless\Core\Bootstrapper\Traits\Migrations\Exceptions;

use Exception;
use Throwable;

class InvalidMigrationFilename extends Exception
{
    public function __construct(public readonly string $invalid_filename, ?Throwable $previous = null)
    {
        parent::__construct("The filename $this->invalid_filename is invalid.", 0, $previous);
    }
}
