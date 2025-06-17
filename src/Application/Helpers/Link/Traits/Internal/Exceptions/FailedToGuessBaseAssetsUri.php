<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Link\Traits\Internal\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToGuessBaseAssetsUri extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Failed to guess base assets URI.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
