<?php

namespace Wordless\Infrastructure\Wordpress\Taxonomy\CustomTaxonomy\Traits\Register;

use Wordless\Application\Helpers\Reserved;
use Wordless\Infrastructure\Wordpress\Taxonomy\CustomTaxonomy\Traits\Register\Validation\Exceptions\InvalidCustomTaxonomyName;
use Wordless\Infrastructure\Wordpress\Taxonomy\CustomTaxonomy\Traits\Register\Validation\Exceptions\ReservedCustomTaxonomyName;

trait Validation
{
    /**
     * @return void
     * @throws InvalidCustomTaxonomyName
     */
    private static function validateFormat(): void
    {
        if (preg_match(
                '/^[\w-]{1,' . self::TAXONOMY_NAME_MAX_LENGTH . '}$/',
                $type_key = static::NAME_KEY ?? ''
            ) !== 1) {
            throw new InvalidCustomTaxonomyName($type_key);
        }
    }

    /**
     * @return void
     * @throws ReservedCustomTaxonomyName
     */
    private static function validateNotReserved(): void
    {
        if (Reserved::isTaxonomyUsedByWordPress($type_key = static::NAME_KEY)) {
            throw new ReservedCustomTaxonomyName($type_key);
        }
    }

    /**
     * @return void
     * @throws InvalidCustomTaxonomyName
     * @throws ReservedCustomTaxonomyName
     */
    private static function validateNameKey(): void
    {
        self::validateFormat();
        self::validateNotReserved();
    }
}
