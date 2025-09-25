<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Makers\MakeListener\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToDetermineStubMounter extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Something went wrong when trying to mount the correct stub mounter on listener making.',
            ExceptionCode::intentional_interrupt->value,
            $previous
        );
    }
}
