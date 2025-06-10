<?php declare(strict_types=1);

namespace Wordless\Core\Bootstrapper\Traits\Console\Exceptions;

use RuntimeException;
use Symfony\Component\Console\Application;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToBootApplication extends RuntimeException
{
    public function __construct(readonly public Application $application, ?Throwable $previous = null)
    {
        parent::__construct(
            'Failed to boot Symfony Console Application',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
