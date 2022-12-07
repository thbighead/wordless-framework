<?php

namespace Wordless\Exceptions;

use Exception;
use Throwable;
use Wordless\Adapters\CustomPost;

class InvalidCustomPostTypeKey extends Exception
{
    private string $invalid_type_key;

    public function __construct(string $invalid_type_key, Throwable $previous = null)
    {
        $this->invalid_type_key = $invalid_type_key;

        parent::__construct(
            "The key '$this->invalid_type_key' is invalid for CPT key. {$this->justification()}",
            0,
            $previous
        );
    }

    /**
     * @return string
     */
    public function getInvalidTypeKey(): string
    {
        return $this->invalid_type_key;
    }

    protected function justification(): string
    {
        return 'A valid key must not exceed '
            . CustomPost::POST_TYPE_KEY_MAX_LENGTH
            . ' characters and may only contain lowercase alphanumeric characters, dashes, and underscores.';
    }
}
