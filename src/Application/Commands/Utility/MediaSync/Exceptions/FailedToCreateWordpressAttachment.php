<?php

namespace App\Commands\MediaSync\Exceptions;

use Exception;
use Throwable;

class FailedToCreateWordpressAttachment extends Exception
{
    private string $absolute_file_path;

    public function __construct(string $absolute_file_path, Throwable $previous = null)
    {
        $this->absolute_file_path = $absolute_file_path;

        parent::__construct(
            "Failed to insert $this->absolute_file_path as an Wordpress attachment into database.",
            0,
            $previous
        );
    }

    public function getAbsoluteFilePath(): string
    {
        return $this->absolute_file_path;
    }
}
