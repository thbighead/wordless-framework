<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Str\Traits;

trait Substring
{
    /**
     * @param string $string
     * @param string $delimiter
     * @return string
     */
    public static function after(string $string, string $delimiter): string
    {
        $substring_position = strpos($string, $delimiter);

        if ($substring_position === false) {
            return $string;
        }

        return substr($string, $substring_position + strlen($delimiter));
    }

    /**
     * @param string $string
     * @param string $delimiter
     * @return string
     */
    public static function afterLast(string $string, string $delimiter): string
    {
        $substring_position = strrpos($string, $delimiter);

        if ($substring_position === false) {
            return $string;
        }

        return substr($string, $substring_position + strlen($delimiter));
    }

    /**
     * @param string $string
     * @param string $delimiter
     * @return string
     */
    public static function before(string $string, string $delimiter): string
    {
        $result = strstr($string, $delimiter, true);

        return $result === false ? $string : $result;
    }

    /**
     * @param string $string
     * @param string $delimiter
     * @return string
     */
    public static function beforeLast(string $string, string $delimiter): string
    {
        $substring_position = strrpos($string, $delimiter);

        if ($substring_position === false) {
            return $string;
        }

        return substr($string, 0, $substring_position);
    }

    /**
     * @param string $string
     * @param string $prefix
     * @param string $suffix
     * @return string
     */
    public static function between(string $string, string $prefix, string $suffix): string
    {
        return static::before(static::after($string, $prefix), $suffix);
    }
}
