<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Seeders\PostsSeeder\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use Wordless\Wordpress\Models\Category;

class FailedToGenerateCategorizedPost extends RuntimeException
{
    public function __construct(public readonly Category $postCategory, ?Throwable $previous = null)
    {
        parent::__construct($message, ExceptionCode::intentional_interrupt->value, $previous);
    }
}
