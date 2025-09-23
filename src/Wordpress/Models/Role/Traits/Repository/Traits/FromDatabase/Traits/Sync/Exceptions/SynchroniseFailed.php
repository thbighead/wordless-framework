<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Role\Traits\Repository\Traits\FromDatabase\Traits\Sync\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class SynchroniseFailed extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Failed to synchronise roles.',
            ExceptionCode::intentional_interrupt->value,
            $previous
        );
    }
}
