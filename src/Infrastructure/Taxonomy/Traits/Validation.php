<?php

namespace Wordless\Infrastructure\Taxonomy\Traits;

use Wordless\Application\Helpers\Reserved;
use Wordless\Exceptions\InvalidCustomTaxonomyName;
use Wordless\Exceptions\ReservedCustomTaxonomyName;

trait Validation
{
    /**
     * @return void
     * @throws InvalidCustomTaxonomyName
     */
    private static function validateFormat()
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
    private static function validateNotReserved()
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
    private static function validateNameKey()
    {
        self::validateFormat();
        self::validateNotReserved();
    }
}
