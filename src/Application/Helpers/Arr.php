<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Wordless\Application\Helpers;

use JsonException;
use Wordless\Application\Helpers\Arr\Contracts\Subjectable;
use Wordless\Application\Helpers\Arr\Exceptions\FailedToFindArrayKey;
use Wordless\Application\Helpers\Arr\Exceptions\FailedToParseArrayKey;

class Arr extends Subjectable
{
    public static function append(array $array, mixed $value): array
    {
        $array[] = $value;

        return $array;
    }

    public static function except(array $array, array $except_keys): array
    {
        $except_array = $except_keys;

        if (!static::isAssociative($except_array)) {
            $except_array = [];

            foreach ($except_keys as $key) {
                $except_array[$key] = $key;
            }
        }

        return array_diff_key($array, $except_array);
    }

    public static function first(array $array, int $quantity = 1): mixed
    {
        array_splice($array, max(1, abs($quantity)));

        return match (sizeof($array)) {
            0 => null,
            1 => $array[static::getFirstKey($array) ?? 0],
            default => $array
        };
    }

    /**
     * @param array $array
     * @param int|string $key
     * @param mixed|null $default
     * @return mixed
     * @throws FailedToParseArrayKey
     */
    public static function get(array $array, int|string $key, mixed $default = null): mixed
    {
        try {
            return static::getOrFail($array, $key);
        } catch (FailedToFindArrayKey) {
            return $default;
        }
    }

    public static function getFirstKey(array $array): string|int|null
    {
        return array_key_first($array);
    }

    /**
     * @param array $array
     * @param int|string $key
     * @return mixed
     * @throws FailedToFindArrayKey
     * @throws FailedToParseArrayKey
     */
    public static function getOrFail(array $array, int|string $key): mixed
    {
        $key = (string)$key;
        $key_pathing = explode('.', $key);
        $first_key = array_shift($key_pathing) ?? throw new FailedToParseArrayKey($key);
        $pointer = $array[$first_key] ?? throw new FailedToFindArrayKey($array, $key, $first_key);

        foreach ($key_pathing as $key_path) {
            if (!isset($pointer[$key_path])) {
                throw new FailedToFindArrayKey($array, $key, $key_path);
            }

            $pointer = $pointer[$key_path];
        }

        return $pointer;
    }

    public static function hasAnyOtherValueThan(array $array, mixed $forbidden_value): bool
    {
        foreach ($array as $item) {
            if ($item !== $forbidden_value) {
                return true;
            }
        }

        return false;
    }

    public static function hasValue(array $array, mixed $value): bool
    {
        return in_array($value, $array, true);
    }

    public static function isAssociative(array $array): bool
    {
        return array_keys($array) !== range(0, count($array) - 1);
    }

    /**
     * @param array $array
     * @param array $only_keys
     * @return array
     * @throws FailedToParseArrayKey
     */
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

    public static function prepend(array $array, mixed $value): array
    {
        array_unshift($array, $value);

        return $array;
    }

    public static function recursiveJoin(array $array_1, array $array_2, array ...$arrays): array
    {
        $joined_array = [];

        self::resolveRecursiveJoin($array_1, $joined_array);
        self::resolveRecursiveJoin($array_2, $joined_array);

        foreach ($arrays as $array) {
            self::resolveRecursiveJoin($array, $joined_array);
        }

        return $joined_array;
    }

    public static function searchValueKey(array $array, mixed $value): int|string|null
    {
        foreach ($array as $key => $item) {
            if ($item === $value) {
                return $key;
            }
        }

        return null;
    }

    /**
     * @param array $array
     * @return string
     * @throws JsonException
     */
    public static function toJson(array $array): string
    {
        return json_encode($array, JSON_THROW_ON_ERROR);
    }

    /**
     * @param array $array
     * @param int|string|null $key
     * @return mixed
     * @throws FailedToParseArrayKey
     */
    public static function unwrap(array $array, int|string|null $key = null): mixed
    {
        $return = $key === null ? static::first($array) : static::get($array, $key);

        while (is_array($return)) {
            $return = static::first($return);
        }

        return $return;
    }

    public static function wrap(mixed $something): array
    {
        if (is_array($something)) {
            return $something;
        }

        return [$something];
    }

    private static function resolveRecursiveJoin(array $array, array &$joined_array): void
    {
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
}
