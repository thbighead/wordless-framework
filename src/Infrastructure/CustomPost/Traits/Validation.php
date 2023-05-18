<?php

namespace Wordless\Infrastructure\CustomPost\Traits;

use Wordless\Application\Helpers\Reserved;
use Wordless\Exceptions\InvalidCustomPostTypeKey;
use Wordless\Exceptions\ReservedCustomPostTypeKey;

trait Validation
{
    /**
     * @return void
     * @throws InvalidCustomPostTypeKey
     */
    private static function validateFormat()
    {
        if (preg_match(
                '/^[\w-]{1,' . self::POST_TYPE_KEY_MAX_LENGTH . '}$/',
                $type_key = static::getTypeKey()
            ) !== 1) {
            throw new InvalidCustomPostTypeKey($type_key);
        }
    }

    /**
     * @return void
     * @throws ReservedCustomPostTypeKey
     */
    private static function validateNotReserved()
    {
        if (Reserved::isPostTypeReservedByWordPress($type_key = static::getTypeKey())) {
            throw new ReservedCustomPostTypeKey($type_key);
        }
    }

    /**
     * @return void
     * @throws InvalidCustomPostTypeKey
     */
    private static function validateTypeKey()
    {
        self::validateFormat();
        self::validateNotReserved();
    }
}
