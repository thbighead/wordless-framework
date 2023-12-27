<?php

namespace Wordless\Application\Helpers\Str\Traits;

use Ramsey\Uuid\Uuid;

trait Boolean
{
    /**
     * @param string $string
     * @param string $substring
     * @return bool
     */
    public static function endsWith(string $string, string $substring): bool
    {
        return str_ends_with($string, $substring);
    }

    /**
     * @param string $string
     * @param string $prefix
     * @param string $suffix
     * @return bool
     */
    public static function isSurroundedBy(string $string, string $prefix, string $suffix): bool
    {
        return static::beginsWith($string, $prefix) && static::endsWith($string, $suffix);
    }

    /**
     * @param string $string
     * @return bool
     */
    public static function isUuid(string $string): bool
    {
        return Uuid::isValid($string);
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
            if (($contains &= ($needle !== '' && mb_strpos($haystack, $needle) !== false)) && $any) {
                return true;
            }

            if (!$contains && !$any) {
                return false;
            }
        }

        return $contains;
    }

    /**
     * @param string $string
     * @param string $substring
     * @return bool
     */
    public static function beginsWith(string $string, string $substring): bool
    {
        return str_starts_with($string, $substring);
    }
}
