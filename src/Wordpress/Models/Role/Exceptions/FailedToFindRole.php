<?php

namespace Wordless\Wordpress\Models\Role\Exceptions;

use InvalidArgumentException;
use Throwable;
use Wordless\Enums\ExceptionCode;

class FailedToFindRole extends InvalidArgumentException
{
    public function __construct(private readonly string $failed_role_string_identifier, ?Throwable $previous = null)
    {
        parent::__construct(
            "Failed to find '$this->failed_role_string_identifier' role .",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
