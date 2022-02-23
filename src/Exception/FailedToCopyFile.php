<?php

namespace Wordless\Exception;

use Exception;
use Throwable;

class FailedToCopyFile extends Exception
{
    public function __construct(string $from, string $to, bool $secure_mode, Throwable $previous = null)
    {
        $security_word_mode = $secure_mode ? 'secure' : 'insecure';

        parent::__construct(
            "Failed to copy from $from to $to in $security_word_mode mode.",
            0,
            $previous
        );
    }
}