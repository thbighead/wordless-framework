<?php

namespace Wordless\Helpers;

use Ramsey\Uuid\Uuid;
use Wordless\Exceptions\InvalidUuidVersion;

class Str
{
    public static function after(string $string, string $delimiter): string
    {
        $substring_position = strpos($string, $delimiter);

        if ($substring_position === false) {
            return $string;
        }

        $substring = substr($string, $substring_position + strlen($delimiter));

        return $substring !== false ? $substring : $string;
    }

    public static function afterLast(string $string, string $delimiter)
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

    public static function beforeLast(string $string, string $delimiter)
    {
        $substring_position = strrpos($string, $delimiter);

        if ($substring_position === false) {
            return $string;
        }

        return substr($string, 0, $substring_position);
    }

    public static function beginsWith(string $string, string $substring): bool
    {
        return substr($string, 0, strlen($substring)) === $substring;
    }

    public static function between(string $string, string $prefix, string $suffix): string
    {
        return self::before(self::after($string, $prefix), $suffix);
    }

    public static function camelCase(string $string): string
    {
        return lcfirst(self::studlyCase($string));
    }

    /**
     * @param string $haystack
     * @param string|string[] $needles
     * @param bool $any
     * @return bool
     */
    public static function contains(string $haystack, $needles, bool $any = true): bool
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

    public static function endsWith(string $string, string $substring): bool
    {
        return substr($string, -strlen($substring)) === $substring;
    }

    public static function finishWith(string $string, string $finish_with): string
    {
        $quoted = preg_quote($finish_with, '/');

        return preg_replace('/(?:' . $quoted . ')+$/u', '', $string) . $finish_with;
    }

    public static function isSurroundedBy(string $string, string $prefix, string $suffix): bool
    {
        return self::beginsWith($string, $prefix) && self::endsWith($string, $suffix);
    }

    public static function isUuid(string $string): bool
    {
        return Uuid::isValid($string);
    }

    public static function limitWords(
        string $string,
        int    $num_words = 15,
        string $limit_marker = '...'
    ): string
    {
        return wp_trim_words($string, $num_words, $limit_marker);
    }

    public static function slugCase(string $string): string
    {
        return self::snakeCase($string, '-');
    }

    public static function snakeCase(string $string, string $delimiter = '_'): string
    {
        if (!ctype_lower($string)) {
            $string = strtolower(preg_replace(
                '/(.)(?=[A-Z])/u',
                "$1$delimiter",
                preg_replace('/\s+/u', '', ucwords($string))
            ));
        }

        return $string;
    }

    public static function startWith(string $string, string $start_with): string
    {
        $quoted = preg_quote($start_with, '/');

        return $start_with . preg_replace('/^(?:' . $quoted . ')+/u', '', $string);
    }

    public static function studlyCase(string $string): string
    {
        $words = explode(' ', str_replace(['-', '_'], ' ', $string));

        $studly_words = array_map(function ($word) {
            return ucfirst($word);
        }, $words);

        return implode($studly_words);
    }

    public static function titleCase(string $string): string
    {
        return mb_convert_case($string, MB_CASE_TITLE, 'UTF-8');
    }

    /**
     * @param int $version
     * @param bool $with_dashes
     * @return string
     * @throws InvalidUuidVersion
     */
    public static function uuid(int $version = 4, bool $with_dashes = true): string
    {
        switch ($version) {
            case 1:
                $uuid = Uuid::uuid1();
                break;
            case 2:
                $uuid = Uuid::uuid2(Uuid::DCE_DOMAIN_PERSON);
                break;
            case 3:
                $uuid = Uuid::uuid3(Uuid::NAMESPACE_DNS, php_uname('n'));
                break;
            case 4:
                $uuid = Uuid::uuid4();
                break;
            case 5:
                $uuid = Uuid::uuid5(Uuid::NAMESPACE_DNS, php_uname('n'));
                break;
            case 6:
                $uuid = Uuid::uuid6();
                break;
            default:
                throw new InvalidUuidVersion($version);
        }

        return $with_dashes ? $uuid->toString() : str_replace('-', '', $uuid->toString());
    }
}
