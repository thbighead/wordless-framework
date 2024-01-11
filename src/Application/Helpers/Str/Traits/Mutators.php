<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Str\Traits;

use InvalidArgumentException;

trait Mutators
{
    /**
     * @param string $string
     * @return string
     * @throws InvalidArgumentException
     */
    public static function unaccented(string $string): string
    {
        return self::getInflector()->unaccent($string);
    }

    /**
     * @param string $string
     * @param string $finish_with
     * @return string
     */
    public static function finishWith(string $string, string $finish_with): string
    {
        $quoted = preg_quote($finish_with, '/');

        return preg_replace("/(?:$quoted)+$/u", '', $string) . $finish_with;
    }

    /**
     * @param string $string
     * @param string $suffix
     * @return string
     */
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
     * @param string $start_with
     * @return string
     */
    public static function startWith(string $string, string $start_with): string
    {
        $quoted = preg_quote($start_with, '/');

        return $start_with . preg_replace("/^(?:$quoted)+/u", '', $string);
    }
}
