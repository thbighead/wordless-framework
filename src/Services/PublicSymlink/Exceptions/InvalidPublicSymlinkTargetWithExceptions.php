<?php

namespace Wordless\Services\PublicSymlink\Exceptions;

use Exception;
use Throwable;

class InvalidPublicSymlinkTargetWithExceptions extends Exception
{
    private string $raw_target_relative_path;

    public function __construct(string $raw_target_relative_path, Throwable $previous = null)
    {
        $this->raw_target_relative_path =$raw_target_relative_path;

        parent::__construct(
            "The relative path \"$this->raw_target_relative_path\" must be a directory to use exceptions.",
            0,
            $previous
        );
    }

    public function getRawTargetRelativePath(): string
    {
        return $this->raw_target_relative_path;
    }
}
