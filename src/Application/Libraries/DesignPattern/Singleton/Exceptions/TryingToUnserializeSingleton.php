<?php

namespace Wordless\Application\Libraries\DesignPattern\Singleton\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Application\Libraries\DesignPattern\Singleton;
use Wordless\Infrastructure\Enums\ExceptionCode;

class TryingToUnserializeSingleton extends ErrorException
{
    public function __construct(private readonly string $singletonClass, ?Throwable $previous = null)
    {
        parent::__construct(
            "Cannot unserialize a $this->singletonClass object because it uses "
            . Singleton::class
            . ' strategy.',
            ExceptionCode::intentional_interrupt->value,
            previous: $previous
        );
    }
}
