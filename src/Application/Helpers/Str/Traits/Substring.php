<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Str\Traits;

trait Substring
{
    final public const DEFAULT_LIMIT_WORDS = 15;
    final public const DEFAULT_TRUNCATE_SIZE = 15;

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

    public static function countSubstring(string $string, string $substring): int
    {
        return substr_count($string, $substring);
    }

    public static function limitWords(
        string $string,
        int    $max_words = self::DEFAULT_LIMIT_WORDS,
        string $limit_marker = '...'
    ): string
    {
        return wp_trim_words(
            $string,
            $max_words <= 0 ? self::DEFAULT_LIMIT_WORDS : $max_words,
            $limit_marker
        );
    }

    public static function truncate(string $string, int $max_chars = self::DEFAULT_TRUNCATE_SIZE): string
    {
        return substr($string, 0, $max_chars <= 0 ? self::DEFAULT_TRUNCATE_SIZE : $max_chars);
    }
}
