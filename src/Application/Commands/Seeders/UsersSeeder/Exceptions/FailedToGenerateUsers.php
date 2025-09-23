<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Seeders\UsersSeeder\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToGenerateUsers extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Could not generate users to seed database.',
            ExceptionCode::intentional_interrupt->value,
            $previous
        );
    }
}
