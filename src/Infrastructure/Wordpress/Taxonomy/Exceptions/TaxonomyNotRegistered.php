<?php

namespace Wordless\Infrastructure\Wordpress\Taxonomy\Exceptions;

use InvalidArgumentException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class TaxonomyNotRegistered extends InvalidArgumentException
{
    public function __construct(private readonly string $not_registered_taxonomy_key, ?Throwable $previous = null)
    {
        parent::__construct(
            "There's no custom taxonomies registered with the following key: $this->not_registered_taxonomy_key",
            ExceptionCode::logic_control->value,
            $previous
        );
    }

    public function getNotRegisteredTaxonomyKey(): string
    {
        return $this->not_registered_taxonomy_key;
    }
}
