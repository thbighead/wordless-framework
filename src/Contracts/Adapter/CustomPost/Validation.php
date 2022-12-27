<?php

namespace Wordless\Contracts\Adapter\CustomPost;

use Wordless\Exceptions\InvalidCustomPostTypeKey;
use Wordless\Exceptions\ReservedCustomPostTypeKey;
use Wordless\Helpers\Reserved;

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
                $type_key = static::TYPE_KEY ?? ''
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
        if (Reserved::isPostTypeReservedByWordPress($type_key = static::TYPE_KEY ?? '')) {
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
