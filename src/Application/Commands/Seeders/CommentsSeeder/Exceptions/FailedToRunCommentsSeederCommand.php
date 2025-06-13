<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Seeders\CommentsSeeder\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToRunCommentsSeederCommand extends RuntimeException
{
    public function __construct(string $message, ?Throwable $previous = null)
    {
        parent::__construct($message, ExceptionCode::development_error->value, $previous);
    }
}
