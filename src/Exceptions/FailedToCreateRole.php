<?php

namespace Wordless\Exceptions;

use Exception;
use Throwable;

class FailedToCreateRole extends Exception
{
    /**
     * @param string $slug_key
     * @param string $name
     * @param bool[] $capabilities
     * @param Throwable|null $previous
     */
    public function __construct(string $slug_key, string $name, array $capabilities, Throwable $previous = null)
    {
        parent::__construct(
            "Failed to create a role named as \"$name\" with key generated as \"$slug_key\" and the following capabilities: "
            . var_export($capabilities, true),
            0,
            $previous
        );
    }
}
