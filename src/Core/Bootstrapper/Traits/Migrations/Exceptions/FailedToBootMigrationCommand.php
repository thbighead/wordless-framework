<?php declare(strict_types=1);

namespace Wordless\Core\Bootstrapper\Traits\Migrations\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToBootMigrationCommand extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Failed to boot a migration file.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
