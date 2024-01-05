<?php

namespace Wordless\Application\Helpers\Taxonomy\Exceptions;

use InvalidArgumentException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class TaxonomyNotRegistered extends InvalidArgumentException
{
    public function __construct(public readonly string $not_registered_taxonomy_key, ?Throwable $previous = null)
    {
        parent::__construct(
            "There's no taxonomies registered with the following key: $this->not_registered_taxonomy_key",
            ExceptionCode::intentional_interrupt->value,
            $previous
        );
    }
}
