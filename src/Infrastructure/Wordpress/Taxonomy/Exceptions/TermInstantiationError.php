<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Taxonomy\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use WP_Term;

class TermInstantiationError extends RuntimeException
{
    public function __construct(public readonly WP_Term|int|string $term, ?Throwable $previous = null)
    {
        $term_text = $this->term instanceof WP_Term ? $this->term->name : $this->term;

        parent::__construct(
            "Something went wrong when trying to instantiate $term_text as a model object.",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
