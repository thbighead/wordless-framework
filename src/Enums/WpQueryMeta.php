<?php declare(strict_types=1);

namespace Wordless\Enums;

use Wordless\Application\Helpers\Str;

class WpQueryMeta
{

    public const RELATION_AND = 'AND';
    public const RELATION_OR = 'OR';

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
