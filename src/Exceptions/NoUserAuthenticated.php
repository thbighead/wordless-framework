<?php

namespace Wordless\Exceptions;

use Exception;
use Throwable;
use Wordless\Wordpress\Models\User;

class NoUserAuthenticated extends Exception
{
    public function __construct(Throwable $previous = null)
    {
        parent::__construct(
            'Trying to load the current authenticated user from ' . User::class,
            0,
            $previous
        );
    }
}
