<?php declare(strict_types=1);

namespace Wordless\Core\Composer\Traits\SetHostFromNginx\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToSetAppHostValueAtDotEnv extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Could not set APP_HOST variable value into .env file.',
            ExceptionCode::intentional_interrupt->value,
            $previous
        );
    }
}
