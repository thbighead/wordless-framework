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

        return static::substring($string, $substring_position + strlen($delimiter));
    }

    /**
     * @param string $string
     * @param string $delimiter
     * @return string
     */
    public static function afterLast(string $string, string $delimiter): string
    {
        if (empty($delimiter)) {
            return $string;
        }

        $substring_position = strrpos($string, $delimiter);

        if ($substring_position === false) {
            return $string;
        }

        return static::substring($string, $substring_position + strlen($delimiter));
    }

    /**
     * @param string $string
     * @param string $delimiter
     * @return string
     */
    public static function before(string $string, string $delimiter): string
    {
        if (empty($delimiter)) {
            return $string;
        }

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

        return static::substring($string, 0, $substring_position);
    }

    public static function between(string $string, string $prefix, ?string $suffix = null): string
    {
        if (($substring = static::after($string, $prefix)) === $string) {
            return $string;
        }

        if (($substring = static::beforeLast($substring, $suffix ?? $prefix)) === $substring) {
            return $string;
        }

        return $substring;
    }

    public static function countSubstring(string $string, string $substring): int
    {
        if (empty($substring)) {
            return 0;
        }

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

    public static function substring(string $string, int $offset, ?int $length = null): string
    {
        return substr($string, $offset, $length);
    }

    public static function truncate(string $string, int $max_chars = self::DEFAULT_TRUNCATE_SIZE): string
    {
        if ($max_chars < 0 && abs($max_chars) >= static::length($string)) {
            $max_chars = self::DEFAULT_TRUNCATE_SIZE;
        }

        return static::substring($string, 0, $max_chars === 0 ? self::DEFAULT_TRUNCATE_SIZE : $max_chars);
    }
}
