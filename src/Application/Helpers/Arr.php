<?php

namespace Wordless\Application\Helpers;

use Wordless\Application\Helpers\Arr\Exceptions\FailedToFindArrayKey;

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

    public static function get(array $array, int|string $key, mixed $default = null): mixed
    {
        try {
            return static::getOrFail($array, $key);
        } catch (FailedToFindArrayKey) {
            return $default;
        }
    }

    /**
     * @param array $array
     * @param int|string $key
     * @return mixed
     * @throws FailedToFindArrayKey
     */
    public static function getOrFail(array $array, int|string $key): mixed
    {
        $key = (string)$key;
        $key_pathing = explode('.', $key);
        $first_key = array_shift($key_pathing);
        $pointer = $array[$first_key] ?? throw new FailedToFindArrayKey($array, $key, $first_key);

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

    public static function only(array $array, array $only_keys): array
    {
        $filtered_array = [];

        foreach ($only_keys as $key_to_filter) {
            try {
                $filtered_array[$key_to_filter] = static::getOrFail($array, $key_to_filter);
            } catch (FailedToFindArrayKey) {
                continue;
            }
        }

        return $filtered_array;
    }

    public static function wrap($something): array
    {
        if (is_array($something)) {
            return $something;
        }

        return [$something];
    }
}
