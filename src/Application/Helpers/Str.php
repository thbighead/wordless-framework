<?php

namespace Wordless\Application\Helpers;

use Doctrine\Inflector\Language;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;
use Wordless\Application\Helpers\Str\Contracts\Subjectable;
use Wordless\Application\Helpers\Str\Enums\UuidVersion;
use Wordless\Application\Helpers\Str\Traits\Internal;

class Str extends Subjectable
{
    use Internal;

    final public const DEFAULT_RANDOM_SIZE = 16;
    final public const DEFAULT_LIMIT_WORDS = 15;
    final public const DEFAULT_TRUNCATE_SIZE = 15;

    public static function after(string $string, string $delimiter): string
    {
        $substring_position = strpos($string, $delimiter);

        if ($substring_position === false) {
            return $string;
        }

        return substr($string, $substring_position + strlen($delimiter));
    }

    public static function afterLast(string $string, string $delimiter): string
    {
        $substring_position = strrpos($string, $delimiter);

        if ($substring_position === false) {
            return $string;
        }

        return substr($string, $substring_position + strlen($delimiter));
    }

    public static function before(string $string, string $delimiter): string
    {
        $result = strstr($string, $delimiter, true);

        return $result === false ? $string : $result;
    }

    public static function beforeLast(string $string, string $delimiter): string
    {
        $substring_position = strrpos($string, $delimiter);

        if ($substring_position === false) {
            return $string;
        }

        return substr($string, 0, $substring_position);
    }

    public static function beginsWith(string $string, string $substring): bool
    {
        return str_starts_with($string, $substring);
    }

    public static function between(string $string, string $prefix, string $suffix): string
    {
        return static::before(static::after($string, $prefix), $suffix);
    }

    public static function camelCase(string $string): string
    {
        return lcfirst(static::pascalCase($string));
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

    public static function countSubstring(string $string, string $substring): int
    {
        return substr_count($string, $substring);
    }

    public static function endsWith(string $string, string $substring): bool
    {
        return str_ends_with($string, $substring);
    }

    public static function finishWith(string $string, string $finish_with): string
    {
        $quoted = preg_quote($finish_with, '/');

        return preg_replace("/(?:$quoted)+$/u", '', $string) . $finish_with;
    }

    public static function isSurroundedBy(string $string, string $prefix, string $suffix): bool
    {
        return static::beginsWith($string, $prefix) && static::endsWith($string, $suffix);
    }

    public static function isUuid(string $string): bool
    {
        return Uuid::isValid($string);
    }

    /**
     * Just an alias. This is the common Case Style name, instead we call it slugCase to ease WordPress developers
     * understanding.
     *
     * @param string $string
     * @return string
     */
    public static function kebabCase(string $string): string
    {
        return static::slugCase($string);
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

    public static function lower(string $string): string
    {
        return mb_strtolower($string);
    }

    public static function pascalCase(string $string): string
    {
        $words = explode(' ', str_replace(['-', '_'], ' ', $string));

        $studly_words = array_map(function ($word) {
            return ucfirst($word);
        }, $words);

        return implode($studly_words);
    }

    /**
     * @param string $string
     * @param string $language
     * @return string
     * @throws InvalidArgumentException
     */
    public static function plural(string $string, string $language = Language::ENGLISH): string
    {
        return self::getInflector($language)->pluralize($string);
    }

    public static function random(int $size = self::DEFAULT_RANDOM_SIZE): string
    {
        return wp_generate_password($size <= 0 ? self::DEFAULT_RANDOM_SIZE : $size, false);
    }

    public static function removeSuffix(string $string, string $suffix): string
    {
        return !static::endsWith($string, $suffix) ? $string : substr($string, 0, -strlen($suffix));
    }

    /**
     * @param string $string
     * @param string|string[] $search
     * @param string|string[] $replace
     * @return string
     */
    public static function replace(string $string, string|array $search, string|array $replace): string
    {
        return str_replace($search, $replace, $string);
    }

    /**
     * @param string $string
     * @param string $language
     * @return string
     * @throws InvalidArgumentException
     */
    public static function singular(string $string, string $language = Language::ENGLISH): string
    {
        return self::getInflector($language)->singularize($string);
    }

    public static function slugCase(string $string): string
    {
        return static::snakeCase($string, '-');
    }

    public static function snakeCase(string $string, string $delimiter = '_'): string
    {
        $string = preg_replace('/[^a-zA-Z0-9]/', $delimiter, $string);

        if (!ctype_lower($string)) {
            $string = strtolower(preg_replace(
                "/([^$delimiter])(?=[A-Z\d])/u",
                "$1$delimiter",
                preg_replace('/\s+/u', '', ucwords($string))
            ));
        }

        return $string;
    }

    public static function startWith(string $string, string $start_with): string
    {
        $quoted = preg_quote($start_with, '/');

        return $start_with . preg_replace("/^(?:$quoted)+/u", '', $string);
    }

    public static function titleCase(string $string): string
    {
        preg_match_all('/(\p{Lu}\p{Ll}*|\d)/u', static::pascalCase($string), $words);

        return mb_convert_case(implode(' ', $words[0]), MB_CASE_TITLE, 'UTF-8');
    }

    public static function truncate(string $string, int $max_chars = self::DEFAULT_TRUNCATE_SIZE): string
    {
        return substr($string, 0, $max_chars <= 0 ? self::DEFAULT_TRUNCATE_SIZE : $max_chars);
    }

    public static function upper(string $string): string
    {
        return mb_strtoupper($string);
    }

    /**
     * @param UuidVersion $version
     * @param bool $with_dashes
     * @return string
     */
    public static function uuid(UuidVersion $version = UuidVersion::four, bool $with_dashes = true): string
    {
        $uuid = match ($version) {
            UuidVersion::one => Uuid::uuid1(),
            UuidVersion::two => Uuid::uuid2(Uuid::DCE_DOMAIN_PERSON),
            UuidVersion::three => Uuid::uuid3(Uuid::NAMESPACE_DNS, php_uname('n')),
            UuidVersion::four => Uuid::uuid4(),
            UuidVersion::five => Uuid::uuid5(Uuid::NAMESPACE_DNS, php_uname('n')),
            UuidVersion::six => Uuid::uuid6(),
        };

        return $with_dashes ? $uuid->toString() : static::replace($uuid->toString(), '-', '');
    }
}
