<?php declare(strict_types=1);

namespace Wordless\Enums;

use Wordless\Application\Helpers\Str;
use Wordless\Wordpress\QueryBuilder\Enums\Operator;

class WpQueryMeta
{
    public const KEY_COMPARE = 'compare';
    public const KEY_META_KEY = 'key';
    public const KEY_META_QUERY = 'meta_query';
    public const KEY_META_VALUE = 'value';
    public const KEY_RELATION = 'relation';
    public const KEY_VALUE_TYPE = 'type';
    public const META_PREFIX = 'meta_';
    public const RELATION_AND = 'AND';
    public const RELATION_OR = 'OR';
    public const TYPE_BINARY = 'BINARY';
    public const TYPE_CHAR = 'CHAR';
    public const TYPE_DATE = 'DATE';
    public const TYPE_DATETIME = 'DATETIME';
    public const TYPE_DECIMAL = 'DECIMAL';
    public const TYPE_NUMERIC = 'NUMERIC';
    public const TYPE_SIGNED = 'SIGNED';
    public const TYPE_TIME = 'TIME';
    public const TYPE_UNSIGNED = 'UNSIGNED';
    public const ZERO_VALUE_KEY = '_wp_zero_value';

    public static function isArrayableValue($value): bool
    {
        if (is_array($value)) {
            return true;
        }

        return is_string($value) && Str::contains($value, ',');
    }

    public static function isAvailableForMetaKeyComparison(string $comparison): bool
    {
        return self::AVAILABLE_FOR_META_KEY_COMPARISONS[$comparison] ?? false;
    }

    public static function isOnlyForArraysComparison(string $comparison): bool
    {
        return self::ONLY_FOR_ARRAY_COMPARISONS[$comparison] ?? false;
    }
}
