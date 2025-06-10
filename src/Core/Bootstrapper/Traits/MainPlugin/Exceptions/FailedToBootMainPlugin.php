<?php declare(strict_types=1);

namespace Wordless\Core\Bootstrapper\Traits\MainPlugin\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToBootMainPlugin extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'The framework Must Use Plugin failed to load somehow.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
