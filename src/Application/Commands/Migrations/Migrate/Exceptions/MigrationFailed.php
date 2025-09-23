<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Migrations\Migrate\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class MigrationFailed extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Migration command failed.',
            ExceptionCode::intentional_interrupt->value,
            $previous
        );
    }
}
