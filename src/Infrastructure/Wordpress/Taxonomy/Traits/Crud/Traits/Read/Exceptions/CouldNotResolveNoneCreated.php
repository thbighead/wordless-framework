<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Crud\Traits\Read\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class CouldNotResolveNoneCreated extends RuntimeException
{
    public function __construct(public readonly string $taxonomy, ?Throwable $previous = null)
    {
        parent::__construct(
            "Something went wrong when trying to check if there was no $this->taxonomy created.",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
