<?php

namespace Wordless\Wordpress\Models\Role\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToCreateRole extends ErrorException
{
    /**
     * @param string $slug_key
     * @param string $name
     * @param bool[] $capabilities
     * @param Throwable|null $previous
     */
    public function __construct(
        private readonly string $slug_key,
        private readonly string $name,
        private readonly array  $capabilities,
        ?Throwable              $previous = null
    )
    {
        parent::__construct(
            "Failed to create a role named as \"$this->name\" with key generated as \"$this->slug_key\" and the following capabilities: "
            . var_export($this->capabilities, true),
            ExceptionCode::development_error->value,
            previous: $previous
        );
    }
}
