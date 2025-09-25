<?php declare(strict_types=1);

namespace Wordless\Core\Bootstrapper\Traits\Entities\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToRegisterWordlessEntity extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Failed to register Wordless Entity.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
