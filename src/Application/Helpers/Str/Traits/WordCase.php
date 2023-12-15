<?php

namespace Wordless\Application\Helpers\Str\Traits;

use InvalidArgumentException;

trait WordCase
{
    private const UNDERSCORE = '_';

    /**
     * @param string $string
     * @return string
     * @throws InvalidArgumentException
     */
    public static function camelCase(string $string): string
    {
        return self::getInflector()->camelize($string);
    }

    /**
     * Just an alias. This is the common Case Style name, instead we call it slugCase to ease WordPress developers
     * understanding.
     *
     * @param string $string
     * @param bool $upper_cased
     * @return string
     * @throws InvalidArgumentException
     */
    public static function kebabCase(string $string, bool $upper_cased = false): string
    {
        return static::slugCase($string, $upper_cased);
    }

    /**
     * @param string $string
     * @return string
     */
    public static function lower(string $string): string
    {
        return mb_strtolower($string);
    }

    /**
     * @param string $string
     * @return string
     * @throws InvalidArgumentException
     */
    public static function lowerKebabCase(string $string): string
    {
        return static::kebabCase($string);
    }

    /**
     * @param string $string
     * @return string
     * @throws InvalidArgumentException
     */
    public static function lowerSlugCase(string $string): string
    {
        return static::slugCase($string);
    }

    /**
     * @param string $string
     * @param string $delimiter
     * @return string
     * @throws InvalidArgumentException
     */
    public static function lowerSnakeCase(string $string, string $delimiter = self::UNDERSCORE): string
    {
        return static::snakeCase($string, $delimiter);
    }

    /**
     * @param string $string
     * @return string
     * @throws InvalidArgumentException
     */
    public static function pascalCase(string $string): string
    {
        return self::getInflector()->classify($string);
    }

    /**
     * @param string $string
     * @param bool $upper_cased
     * @return string
     * @throws InvalidArgumentException
     */
    public static function slugCase(string $string, bool $upper_cased = false): string
    {
        return static::snakeCase($string, '-', $upper_cased);
    }

    /**
     * @param string $string
     * @param string $delimiter
     * @param bool $upper_cased
     * @return string
     * @throws InvalidArgumentException
     */
    public static function snakeCase(
        string $string,
        string $delimiter = self::UNDERSCORE,
        bool   $upper_cased = false
    ): string
    {
        $string = preg_replace('/[^a-zA-Z0-9]/', $delimiter, static::unaccented($string));

        if (!static::contains($string, $delimiter)) {
            $string = preg_replace(
                "/([^$delimiter])(?=[A-Z\d])/u",
                "$1$delimiter",
                $string
            );
        }

        return $upper_cased ? mb_strtoupper($string) : mb_strtolower($string);
    }

    /**
     * @param string $string
     * @return string
     * @throws InvalidArgumentException
     */
    public static function titleCase(string $string): string
    {
        preg_match_all('/(\p{Lu}\p{Ll}*|\d)/u', static::pascalCase($string), $words);

        return mb_convert_case(implode(' ', $words[0]), MB_CASE_TITLE, 'UTF-8');
    }

    /**
     * @param string $string
     * @return string
     */
    public static function upper(string $string): string
    {
        return mb_strtoupper($string);
    }

    /**
     * @param string $string
     * @return string
     * @throws InvalidArgumentException
     */
    public static function upperKebabCase(string $string): string
    {
        return static::kebabCase($string, true);
    }

    /**
     * @param string $string
     * @return string
     * @throws InvalidArgumentException
     */
    public static function upperSlugCase(string $string): string
    {
        return static::slugCase($string, true);
    }

    /**
     * @param string $string
     * @param string $delimiter
     * @return string
     * @throws InvalidArgumentException
     */
    public static function upperSnakeCase(string $string, string $delimiter = self::UNDERSCORE): string
    {
        return static::snakeCase($string, $delimiter, true);
    }
}
