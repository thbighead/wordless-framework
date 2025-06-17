<?php declare(strict_types=1);

namespace Wordless\Application\Commands\DistributeFront\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToRunDistributeFrontCommand extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Failed to run distribute front command.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
