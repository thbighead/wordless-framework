<?php

namespace Wordless\Exceptions;

use Exception;
use Throwable;
use Wordless\Adapters\Taxonomy;

class InvalidCustomTaxonomyName extends Exception
{
    private string $invalid_taxonomy_name;

    public function __construct(string $invalid_taxonomy_name, Throwable $previous = null)
    {
        $this->invalid_taxonomy_name = $invalid_taxonomy_name;

        parent::__construct(
            "The key '$this->invalid_taxonomy_name' is invalid for taxonomy name. {$this->justification()}",
            0,
            $previous
        );
    }

    public function getInvalidTaxonomyName(): string
    {
        return $this->invalid_taxonomy_name;
    }

    protected function justification(): string
    {
        return 'A valid name must not exceed '
            . Taxonomy::TAXONOMY_NAME_MAX_LENGTH
            . ' characters and may only contain lowercase non-numeric characters and underscores.';
    }
}
