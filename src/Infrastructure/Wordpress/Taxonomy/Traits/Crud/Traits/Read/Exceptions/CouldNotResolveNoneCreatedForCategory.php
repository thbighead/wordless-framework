<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Crud\Traits\Read\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class CouldNotResolveNoneCreatedForCategory extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Something went wrong when trying to check if there was no categories created.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
