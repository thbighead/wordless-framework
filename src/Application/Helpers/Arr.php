<?php

namespace Wordless\Application\Helpers;

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

    public static function isAssociative(array $array): bool
    {
        return array_keys($array) !== range(0, count($array) - 1);
    }

    public static function searchValue(array $array, $value): int|string|null
    {
        foreach ($array as $key => $item) {
            if ($item === $value) {
                return $key;
            }
        }

        return null;
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
