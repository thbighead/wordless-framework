<?php

namespace Wordless\Exceptions;

use Exception;
use Throwable;

class WordPressFailedToFindRole extends Exception
{
    /**
     * PathNotFoundException constructor.
     *
     * @param string $failed_role_string_identifier
     * @param Throwable|null $previous
     */
    public function __construct(string $failed_role_string_identifier, Throwable $previous = null)
    {
        parent::__construct("Failed to find '$failed_role_string_identifier' role .", 1, $previous);
    }
}