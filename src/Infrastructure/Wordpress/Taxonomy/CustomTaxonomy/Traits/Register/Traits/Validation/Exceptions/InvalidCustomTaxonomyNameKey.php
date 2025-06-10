<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Taxonomy\CustomTaxonomy\Traits\Register\Traits\Validation\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class InvalidCustomTaxonomyNameKey extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('Invalid custom taxonomy name key.', ExceptionCode::development_error->value, $previous);
    }
}
