<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Taxonomy\CustomTaxonomy\Traits\Register\Traits;

use Wordless\Application\Helpers\Reserved;
use Wordless\Infrastructure\Wordpress\Taxonomy\CustomTaxonomy\Traits\Register\Traits\Validation\Exceptions\InvalidCustomTaxonomyNameFormat;
use Wordless\Infrastructure\Wordpress\Taxonomy\CustomTaxonomy\Traits\Register\Traits\Validation\Exceptions\InvalidCustomTaxonomyNameKey;
use Wordless\Infrastructure\Wordpress\Taxonomy\CustomTaxonomy\Traits\Register\Traits\Validation\Exceptions\ReservedCustomTaxonomyNameFormat;

trait Validation
{
    /**
     * @return void
     * @throws InvalidCustomTaxonomyNameFormat
     */
    private static function validateFormat(): void
    {
        if (preg_match(
                '/^[\w-]{1,' . self::TAXONOMY_NAME_MAX_LENGTH . '}$/',
                $type_key = static::NAME_KEY ?? ''
            ) !== 1) {
            throw new InvalidCustomTaxonomyNameFormat($type_key);
        }
    }

    /**
     * @return void
     * @throws ReservedCustomTaxonomyNameFormat
     */
    private static function validateNotReserved(): void
    {
        if (Reserved::isTaxonomyUsedByWordPress($type_key = static::NAME_KEY)) {
            throw new ReservedCustomTaxonomyNameFormat($type_key);
        }
    }

    /**
     * @return void
     * @throws InvalidCustomTaxonomyNameKey
     */
    private static function validateNameKey(): void
    {
        try {
            self::validateFormat();
            self::validateNotReserved();
        } catch (InvalidCustomTaxonomyNameFormat|ReservedCustomTaxonomyNameFormat $exception) {
            throw new InvalidCustomTaxonomyNameKey($exception);
        }
    }
}
