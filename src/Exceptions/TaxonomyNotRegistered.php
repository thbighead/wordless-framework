<?php

namespace Wordless\Exceptions;

use Exception;
use Throwable;

class TaxonomyNotRegistered extends Exception
{
    private string $not_registered_taxonomy_key;

    public function __construct(string $not_registered_taxonomy_key, Throwable $previous = null)
    {
        $this->not_registered_taxonomy_key = $not_registered_taxonomy_key;

        parent::__construct(
            "There's no custom taxonomies registered with the following key: $not_registered_taxonomy_key",
            0,
            $previous
        );
    }

    /**
     * @return string
     */
    public function getNotRegisteredTaxonomyKey(): string
    {
        return $this->not_registered_taxonomy_key;
    }
}
