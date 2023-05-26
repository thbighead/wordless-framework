<?php

namespace Wordless\Infrastructure\Taxonomy\Traits\Register;

use Wordless\Application\Helpers\Reserved;
use Wordless\Exceptions\ReservedCustomTaxonomyName;
use Wordless\Infrastructure\Taxonomy\Traits\Register\Validation\Exceptions\InvalidCustomTaxonomyName;

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
                $type_key = static::getNameKey() ?? ''
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
        if (Reserved::isTaxonomyReservedByWordPress($type_key = static::getNameKey())) {
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
