<?php

namespace Wordless\Exceptions;

use Exception;
use Throwable;

class PostTypeNotRegistered extends Exception
{
    private string $not_registered_post_type_key;

    public function __construct(string $not_registered_post_type_key, Throwable $previous = null)
    {
        $this->not_registered_post_type_key = $not_registered_post_type_key;

        parent::__construct(
            "There's no custom posts registered with the following key: $not_registered_post_type_key",
            0,
            $previous
        );
    }

    /**
     * @return string
     */
    public function getNotRegisteredPostTypeKey(): string
    {
        return $this->not_registered_post_type_key;
    }
}
