<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Crud\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToFindOrCreate extends RuntimeException
{
    public function __construct(
        public readonly string $term_name,
        public readonly string $taxonomy,
        ?Throwable             $previous = null
    )
    {
        parent::__construct(
            "Failed to find or create a term named $this->term_name as a $this->taxonomy.",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
