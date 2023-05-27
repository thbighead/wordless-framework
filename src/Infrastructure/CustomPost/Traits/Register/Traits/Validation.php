<?php

namespace Wordless\Infrastructure\CustomPost\Traits\Register\Traits;

use Wordless\Application\Helpers\Reserved;
use Wordless\Infrastructure\CustomPost\Traits\Register\Traits\Validation\Exceptions\InvalidCustomPostTypeKey;
use Wordless\Infrastructure\CustomPost\Traits\Register\Traits\Validation\Exceptions\ReservedCustomPostTypeKey;

trait Validation
{
    /**
     * @return void
     * @throws InvalidCustomPostTypeKey
     */
    private static function validateFormat(): void
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
    private static function validateNotReserved(): void
    {
        if (Reserved::isPostTypeUsedByWordPress($type_key = static::getTypeKey())) {
            throw new ReservedCustomPostTypeKey($type_key);
        }
    }

    /**
     * @return void
     * @throws InvalidCustomPostTypeKey
     */
    private static function validateTypeKey(): void
    {
        self::validateFormat();
        self::validateNotReserved();
    }
}
