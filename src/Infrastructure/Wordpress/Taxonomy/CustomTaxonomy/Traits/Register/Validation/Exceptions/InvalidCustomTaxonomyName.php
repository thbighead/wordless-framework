<?php

namespace Wordless\Infrastructure\Wordpress\Taxonomy\CustomTaxonomy\Traits\Register\Validation\Exceptions;

use InvalidArgumentException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use Wordless\Infrastructure\Wordpress\Taxonomy\CustomTaxonomy;

class InvalidCustomTaxonomyName extends InvalidArgumentException
{
    public function __construct(private readonly string $invalid_taxonomy_name, ?Throwable $previous = null)
    {
        parent::__construct(
            "The key '$this->invalid_taxonomy_name' is invalid for taxonomy name. {$this->justification()}",
            ExceptionCode::development_error->value,
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
            . CustomTaxonomy::TAXONOMY_NAME_MAX_LENGTH
            . ' characters and may only contain lowercase non-numeric characters and underscores.';
    }
}
