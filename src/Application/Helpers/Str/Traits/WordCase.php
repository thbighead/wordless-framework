<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Str\Traits;

use InvalidArgumentException;
use Wordless\Application\Helpers\Str\Enums\Encoding;

trait WordCase
{
    final public const UNDERSCORE = '_';

    /**
     * @param string $string
     * @return string
     * @throws InvalidArgumentException
     */
    public static function camelCase(string $string): string
    {
        return self::getInflector()->camelize(static::snakeCase($string));
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
     * @param Encoding|null $encoding
     * @return string
     */
    public static function lower(string $string, ?Encoding $encoding = null): string
    {
        return mb_strtolower($string, $encoding?->value);
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
        return self::getInflector()->classify(static::lowerSnakeCase($string));
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
     * @param Encoding|null $encoding
     * @return string
     * @throws InvalidArgumentException
     */
    public static function snakeCase(
        string $string,
        string $delimiter = self::UNDERSCORE,
        bool   $upper_cased = false,
        ?Encoding $encoding = null
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

        return $upper_cased ? static::upper($string, $encoding) : static::lower($string, $encoding);
    }

    /**
     * @param string $string
     * @param Encoding|null $encoding
     * @return string
     * @throws InvalidArgumentException
     */
    public static function titleCase(string $string, ?Encoding $encoding = Encoding::UTF_8): string
    {
        preg_match_all('/(\p{Lu}\p{Ll}*|\d)/u', static::pascalCase($string), $words);

        return mb_convert_case(implode(' ', $words[0]), MB_CASE_TITLE, $encoding?->value);
    }

    /**
     * @param string $string
     * @param Encoding|null $encoding
     * @return string
     */
    public static function upper(string $string, ?Encoding $encoding = null): string
    {
        return mb_strtoupper($string, $encoding?->value);
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
