<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Repository\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToFind extends RuntimeException
{
    public function __construct(public readonly int|string $term, ?Throwable $previous = null)
    {
        parent::__construct(
            "Couldn't try to find the term $this->term.",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
