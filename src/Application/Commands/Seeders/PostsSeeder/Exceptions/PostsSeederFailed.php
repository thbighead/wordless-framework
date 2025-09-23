<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Seeders\PostsSeeder\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class PostsSeederFailed extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Could not seed posts.',
            ExceptionCode::intentional_interrupt->value,
            $previous
        );
    }
}
