<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register\Traits;

use Wordless\Application\Helpers\Reserved;
use Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register\Traits\Validation\Exceptions\InvalidCustomPostTypeKeyFormat;
use Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register\Traits\Validation\Exceptions\ReservedCustomPostTypeKeyFormat;
use Wordless\Wordpress\Models\PostType;

trait Validation
{
    /**
     * @return void
     * @throws InvalidCustomPostTypeKeyFormat
     */
    private static function validateFormat(): void
    {
        if (preg_match(
                '/^[\w-]{1,' . PostType::KEY_MAX_LENGTH . '}$/',
                $type_key = static::getTypeKey()
            ) !== 1) {
            throw new InvalidCustomPostTypeKeyFormat($type_key);
        }
    }

    /**
     * @return void
     * @throws ReservedCustomPostTypeKeyFormat
     */
    private static function validateNotReserved(): void
    {
        if (Reserved::isPostTypeUsedByWordPress($type_key = static::getTypeKey())) {
            throw new ReservedCustomPostTypeKeyFormat($type_key);
        }
    }

    /**
     * @return void
     */
    private static function validateTypeKey(): void
    {
        self::validateFormat();
        self::validateNotReserved();
    }
}
