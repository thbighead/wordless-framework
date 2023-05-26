<?php

namespace Wordless\Wordpress\Models\PostType\Exceptions;

use InvalidArgumentException;
use Throwable;
use Wordless\Enums\ExceptionCode;

class PostTypeNotRegistered extends InvalidArgumentException
{
    public function __construct(private readonly string $not_registered_post_type_key, ?Throwable $previous = null)
    {
        parent::__construct(
            "There's no custom posts registered with the following key: $this->not_registered_post_type_key",
            ExceptionCode::development_error->value,
            $previous
        );
    }

    public function getNotRegisteredPostTypeKey(): string
    {
        return $this->not_registered_post_type_key;
    }
}
