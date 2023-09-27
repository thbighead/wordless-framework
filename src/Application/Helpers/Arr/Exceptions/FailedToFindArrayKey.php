<?php

namespace Wordless\Application\Helpers\Arr\Exceptions;

use ErrorException;
use Throwable;

class FailedToFindArrayKey extends ErrorException
{
    private array $array;
    private string $full_key_string;
    private string $partial_key_which_failed;

    public function __construct(
        array     $array,
        string    $full_key_string,
        string    $partial_key_which_failed,
        Throwable $previous = null
    )
    {
        $this->array = $array;
        $this->full_key_string = $full_key_string;
        $this->partial_key_which_failed = $partial_key_which_failed;

        parent::__construct(
            "Failed to retrieve '$this->full_key_string' key from an array at '$this->partial_key_which_failed'.",
            0,
            $previous
        );
    }

    public function getArray(): array
    {
        return $this->array;
    }

    public function getFullKeyString(): string
    {
        return $this->full_key_string;
    }

    public function getPartialKeyWhichFailed(): string
    {
        return $this->partial_key_which_failed;
    }
}
