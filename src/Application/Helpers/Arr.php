<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Wordless\Application\Helpers;

use JsonException;
use Wordless\Application\Helpers\Arr\Contracts\Subjectable;
use Wordless\Application\Helpers\Arr\Exceptions\ArrayKeyAlreadySet;
use Wordless\Application\Helpers\Arr\Exceptions\EmptyArrayHasNoIndex;
use Wordless\Application\Helpers\Arr\Exceptions\FailedToFindArrayKey;
use Wordless\Application\Helpers\Arr\Exceptions\FailedToParseArrayKey;

class Arr extends Subjectable
{
    /**
     * @param array $array
     * @param mixed $value
     * @param string|int|null $with_key
     * @return array
     * @throws ArrayKeyAlreadySet
     */
    public static function append(array $array, mixed $value, string|int|null $with_key = null): array
    {
        if ($with_key === null) {
            $array[] = $value;

            return $array;
        }

        if (static::hasKey($array, $with_key)) {
            throw new ArrayKeyAlreadySet($array, $with_key, __METHOD__);
        }

        $array[$with_key] = $value;

        return $array;
    }

    public static function except(array $array, string|int ...$except_keys): array
    {
        $except_array = [];

        foreach ($except_keys as $key) {
            $except_array[$key] = $key;
        }

        return array_diff_key($array, $except_array);
    }

    public static function first(array $array, int $quantity = 1): mixed
    {
        array_splice($array, max(1, $quantity));

        return match (static::size($array)) {
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

    public static function getIndexOfKey(array $array, string|int $key): ?int
    {
        if (!static::isAssociative($array)) {
            return isset($array[$key]) ? $key : null;
        }

        return array_flip(array_keys($array))[$key] ?? null;
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

        if (empty($array)) {
            throw new FailedToFindArrayKey($array, $key, $key);
        }

        $key_pathing = self::parseArrayKey($key);
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

    public static function hasKey(array $array, string|int $key): bool
    {
        return key_exists($key, $array);
    }

    public static function hasValue(array $array, mixed $value): bool
    {
        return in_array($value, $array, true);
    }

    public static function isAssociative(array $array): bool
    {
        try {
            return range(0, static::lastIndex($array)) !== array_keys($array);
        } catch (EmptyArrayHasNoIndex) {
            return true;
        }
    }

    public static function isEmpty(array $array): bool
    {
        return empty($array);
    }

    /**
     * @param array $array
     * @return int
     * @throws EmptyArrayHasNoIndex
     */
    public static function lastIndex(array $array): int
    {
        if (empty($array)) {
            throw new EmptyArrayHasNoIndex;
        }

        return static::size($array) - 1;
    }

    /**
     * @param array $array
     * @param string|int ...$only_keys
     * @return array
     * @throws FailedToParseArrayKey
     */
    public static function only(array $array, string|int ...$only_keys): array
    {
        if (empty($array) || empty($only_keys)) {
            return [];
        }

        $filtered_array = [];

        foreach ($only_keys as $key_to_filter) {
            try {
                $value = static::getOrFail($array, $key_to_filter);
            } catch (FailedToFindArrayKey) {
                continue;
            }

            $pointer = null;

            foreach (self::parseArrayKey((string)$key_to_filter) as $parsed_key) {
                if (is_null($pointer)) {
                    $filtered_array[$parsed_key] = [];
                    $pointer = &$filtered_array[$parsed_key];

                    continue;
                }

                $pointer[$parsed_key] = [];
                $pointer = &$pointer[$parsed_key];
            }

            $pointer = $value;
            unset($pointer);
        }

        return $filtered_array;
    }

    public static function packBy(array $array, int $by): array
    {
        if (empty($array)) {
            return [];
        }

        $result = [];
        $preserve_keys = static::isAssociative($array);
        $by = max(1, abs($by));
        $number_of_packs = (int)(
            floor(($array_size = static::size($array)) / $by) + ($array_size % $by > 0 ? 1 : 0)
        );

        for ($i = 0; $i < $number_of_packs; $i++) {
            $result[] = array_slice($array, $i * $by, $by, $preserve_keys);
        }

        return $result;
    }

    /**
     * @param array $array
     * @param mixed $value
     * @param string|int|null $with_key
     * @return array
     * @throws ArrayKeyAlreadySet
     */
    public static function prepend(array $array, mixed $value, string|int|null $with_key = null): array
    {
        $with_key ??= 0;

        if (!static::isAssociative($array) && $with_key === 0) {
            array_unshift($array, $value);

            return $array;
        }

        if (static::hasKey($array, $with_key)) {
            throw new ArrayKeyAlreadySet($array, $with_key, __METHOD__);
        }

        $new_array = [$with_key => $value];

        foreach ($array as $item_key => $item_value) {
            $new_array[$item_key] = $item_value;
        }

        return $new_array;
    }

    public static function print(array $array): string
    {
        return rtrim(var_export($array, true));
    }

    /**
     * @param array $array
     * @param int $index
     * @param mixed $value
     * @param string|int|null $with_key
     * @return array
     * @throws ArrayKeyAlreadySet
     */
    public static function pushValueIntoIndex(
        array           $array,
        int             $index,
        mixed           $value,
        string|int|null $with_key = null
    ): array
    {
        if ($with_key !== null && static::hasKey($array, $with_key)) {
            throw new ArrayKeyAlreadySet($array, $with_key, 'push value into index');
        }

        if (($index = abs($index)) === 0) {
            return static::prepend($array, $value, $with_key);
        }

        try {
            if ($index >= static::lastIndex($array)) {
                return static::append($array, $value, $with_key);
            }
        } catch (EmptyArrayHasNoIndex) {
            return static::prepend($array, $value, $with_key);
        }

        $preserve_keys = static::isAssociative($array);
        $new_array = array_slice($array, 0, $index, $preserve_keys);
        $second_part = array_slice($array, $index, preserve_keys: true);

        if (!$preserve_keys) {
            return array_merge(
                $new_array,
                $with_key === null ? [$value] : [$with_key => $value],
                $second_part
            );
        }

        if ($with_key === null) {
            $temp_value = Str::uuid();
            $array[] = $temp_value;
            $with_key = static::searchValueKey($array, $temp_value);
        }

        $new_array[$with_key] = $value;

        foreach ($second_part as $second_part_key => $second_part_value) {
            $new_array[$second_part_key] = $second_part_value;
        }

        return $new_array;
    }

    public static function recursiveJoin(array $initial_array, array $array, array ...$arrays): array
    {
        if (empty($array) && empty($arrays)) {
            return $initial_array;
        }

        $joined_array = [];

        self::resolveRecursiveJoin($initial_array, $joined_array);
        self::resolveRecursiveJoin($array, $joined_array);

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

    public static function size(array $array): int
    {
        return count($array);
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

    /**
     * @param string $full_key
     * @return string[]
     * @throws FailedToParseArrayKey
     */
    private static function parseArrayKey(string $full_key): array
    {
        if (empty($parsed_key = explode('.', $full_key))) {
            throw new FailedToParseArrayKey($full_key);
        }

        return $parsed_key;
    }

    private static function resolveRecursiveJoin(array $array, array &$joined_array): void
    {
        foreach ($array as $key => $value) {
            if (!isset($joined_array[$key]) || !is_array($joined_array[$key])) {
                $joined_array[$key] = $value;
                continue;
            }

            if (!is_array($value)) {
                $joined_array[$key] = $value;
                continue;
            }

            $joined_array[$key] = static::recursiveJoin($joined_array[$key], $value);
        }
    }
}
