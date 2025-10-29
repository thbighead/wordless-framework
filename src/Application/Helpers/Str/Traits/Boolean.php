<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Wordless\Application\Helpers\Str\Traits;

use Ramsey\Uuid\Uuid;
use Wordless\Application\Helpers\Str\Exceptions\JsonDecodeError;

trait Boolean
{
    /**
     * @param string $string
     * @param string $substring
     * @return bool
     */
    public static function beginsWith(string $string, string $substring): bool
    {
        return str_starts_with($string, $substring);
    }

    /**
     * @param string $haystack
     * @param string|string[] $needles
     * @param bool $any
     * @return bool
     */
    public static function contains(string $haystack, string|array $needles, bool $any = true): bool
    {
        $contains = true;

        foreach ((array)$needles as $needle) {
            if (($contains &= ($needle !== '' && mb_strpos(
                        $haystack,
                        $needle
                    ) !== false)) && $any) {
                return true;
            }

            if (!$contains && !$any) {
                return false;
            }
        }

        return (bool)$contains;
    }

    /**
     * @param string $string
     * @param string $substring
     * @return bool
     */
    public static function endsWith(string $string, string $substring): bool
    {
        return str_ends_with($string, $substring);
    }

    public static function isEmpty(string $string): bool
    {
        return static::length($string) === 0;
    }

    public static function isJson(string $string): bool
    {
        try {
            static::jsonDecode($string);

            return true;
        } catch (JsonDecodeError) {
            return false;
        }
    }

    public static function isWrappedBy(string $string, string $prefix, ?string $suffix = null): bool
    {
        return static::beginsWith($string, $prefix) && static::endsWith($string, $suffix ?? $prefix);
    }

    /**
     * @param string $string
     * @return bool
     */
    public static function isUuid(string $string): bool
    {
        return Uuid::isValid($string);
    }
}
