<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\DirectoryFiles\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToTravelDirectories extends RuntimeException
{
    public function __construct(
        public readonly string $to,
        public readonly string $back_to,
        ?Throwable             $previous = null
    )
    {
        parent::__construct(
            "Failed to go to $this->to execute function and go back to $this->back_to.",
            ExceptionCode::intentional_interrupt->value,
            $previous
        );
    }
}
