<?php

namespace Wordless\Exceptions;

use Exception;
use Throwable;
use Wordless\Contracts\Traits\Singleton;

class TryingToUnserializeSingleton extends Exception
{
    public function __construct(string $singletonClass, Throwable $previous = null)
    {
        parent::__construct(
            "Cannot unserialize a $singletonClass object because it uses "
            . Singleton::class
            . ' strategy.',
            0,
            $previous
        );
    }
}
