<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Seeders\PostsSeeder\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToPopulateCategories extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'There was no categories created and the population failed.',
            ExceptionCode::intentional_interrupt->value,
            $previous
        );
    }
}
