<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Taxonomy\CustomTaxonomy\Traits\Register\Exceptions;

use InvalidArgumentException;
use Throwable;
use Wordless\Application\Helpers\GetType;
use Wordless\Infrastructure\Enums\ExceptionCode;

class InvalidObjectTypeAssociationToTaxonomy extends InvalidArgumentException
{
    public function __construct(
        public readonly mixed $objectType,
        public readonly string $taxonomy_name_key,
        ?Throwable $previous = null
    )
    {
        parent::__construct($this->mountMessage(), ExceptionCode::development_error->value, $previous);
    }

    private function isObjectTypeStringCastable(): bool
    {
        return is_string($this->objectType) || GetType::isStringable($this->objectType);
    }

    private function mountMessage(): string
    {
        if ($this->isObjectTypeStringCastable()) {
            return "$this->objectType cannot be associated to taxonomies (tried with $this->taxonomy_name_key).";
        }

        return "Invalid object type trying to be associated with taxonomy $this->taxonomy_name_key (it's not even stringable).";
    }
}
