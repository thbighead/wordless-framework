<?php

namespace Wordless\Helpers;

use Wordless\Exceptions\FailedToFindArrayKey;

class Arr
{
    public static function except(array $array, array $except_keys): array
    {
        $except_array = $except_keys;

        if (!self::isAssociative($except_array)) {
            $except_array = [];
            foreach ($except_keys as $key) {
                $except_array[$key] = $key;
            }
        }

        return array_diff_key($array, $except_array);
    }

    /**
     * @param array $array
     * @param mixed $key
     * @param mixed|null $default
     * @return mixed|null
     */
    public static function get(array $array, $key, $default = null)
    {
        try {
            return static::getOrFail($array, $key);
        } catch (FailedToFindArrayKey $exception) {
            return $default;
        }
    }

    /**
     * @param array $array
     * @param mixed $key
     * @return mixed|null
     * @throws FailedToFindArrayKey
     */
    public static function getOrFail(array $array, $key)
    {
        $key_pathing = explode('.', $key);
        $first_key = array_shift($key_pathing);

        if (!isset($array[$first_key])) {
            throw new FailedToFindArrayKey($array, $key, $first_key);
        }

        $pointer = $array[$first_key];

        foreach ($key_pathing as $key_path) {
            if (!isset($pointer[$key_path])) {
                throw new FailedToFindArrayKey($array, $key, $key_path);
            }

            $pointer = $pointer[$key_path];
        }

        return $pointer;
    }

    public static function isAssociative(array $array): bool
    {
        return array_keys($array) !== range(0, count($array) - 1);
    }

    public static function recursiveJoin(array ...$arrays): array
    {
        $joined_array = [];

        foreach ($arrays as $array) {
            foreach ($array as $key => $value) {
                if (!isset($joined_array[$key])) {
                    $joined_array[$key] = $value;
                    continue;
                }

                if (!is_array($joined_array[$key])) {
                    $joined_array[$key] = $value;
                    continue;
                }

                if (!is_array($value)) {
                    $joined_array[$key][] = $value;
                    continue;
                }

                $joined_array[$key] = static::recursiveJoin($joined_array[$key], $value);
            }
        }

        return $joined_array;
    }

    public static function wrap($something): array
    {
        if (is_array($something)) {
            return $something;
        }

        return [$something];
    }
}
