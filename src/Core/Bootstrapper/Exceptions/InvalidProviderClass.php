<?php declare(strict_types=1);

namespace Wordless\Core\Bootstrapper\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use Wordless\Infrastructure\Provider;

class InvalidProviderClass extends ErrorException
{
    public function __construct(private readonly string $providerClass, ?Throwable $previous = null)
    {
        parent::__construct(
            "Class $this->providerClass isn't a " . Provider::class,
            ExceptionCode::development_error->value,
            previous: $previous
        );
    }
}
