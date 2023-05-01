<?php

namespace Wordless\Abstractions\Enums;

use Wordless\Helpers\Str;

class WpQueryMeta
{
    public const COMPARE_DIFFERENT = QueryComparison::DIFFERENT;
    public const COMPARE_BETWEEN = 'BETWEEN';
    public const COMPARE_EQUAL = QueryComparison::EQUAL;
    public const COMPARE_EXISTS = 'EXISTS';
    public const COMPARE_GREATER_THAN = QueryComparison::GREATER_THAN;
    public const COMPARE_GREATER_THAN_OR_EQUAL = QueryComparison::GREATER_THAN_OR_EQUAL;
    public const COMPARE_IN = 'IN';
    public const COMPARE_LESS_THAN = QueryComparison::LESS_THAN;
    public const COMPARE_LESS_THAN_OR_EQUAL = QueryComparison::LESS_THAN_OR_EQUAL;
    public const COMPARE_LIKE = QueryComparison::LIKE;
    public const COMPARE_NOT_BETWEEN = 'NOT BETWEEN';
    public const COMPARE_NOT_EXISTS = 'NOT EXISTS';
    public const COMPARE_NOT_LIKE = 'NOT LIKE';
    public const COMPARE_NOT_IN = 'NOT IN';
    public const COMPARE_REGEX = 'REGEXP';
    public const COMPARE_LIKE_REGEX = 'RLIKE';
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
    private const ONLY_FOR_ARRAY_COMPARISONS = [
        self::COMPARE_IN => true,
        self::COMPARE_NOT_IN => true,
        self::COMPARE_BETWEEN => true,
        self::COMPARE_NOT_BETWEEN => true,
    ];
    private const AVAILABLE_FOR_META_KEY_COMPARISONS = [
        self::COMPARE_EXISTS => true,
        self::COMPARE_NOT_EXISTS => true,
    ];

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
