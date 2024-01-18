<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums;

use Wordless\Application\Helpers\Str;
use Wordless\Wordpress\QueryBuilder\Enums\Operator;

enum Comparison: string
{
    case between = 'BETWEEN';
    case different = Operator::different->value;
    case equal = Operator::equal->value;
    case exists = 'EXISTS';
    case greater_than = Operator::greater_than->value;
    case greater_than_or_equal = Operator::greater_than_or_equal->value;
    case in = 'IN';
    case less_than = Operator::less_than->value;
    case less_than_or_equal = Operator::less_than_or_equal->value;
    case like = Operator::like->value;
    case like_regex = 'RLIKE';
    case not_between = 'NOT BETWEEN';
    case not_exists = 'NOT EXISTS';
    case not_like = 'NOT LIKE';
    case not_in = 'NOT IN';
    case regex = 'REGEXP';

    public function isAvailableForMetaKeyComparison(): bool
    {
        return in_array($this, [
            self::exists,
            self::not_exists
        ]);
    }

    public function isOnlyForArraysComparison(): bool
    {
        return in_array($this, [
            self::in,
            self::not_in,
            self::between,
            self::not_between,
        ]);
    }

    public function isArrayableValue($value): bool
    {
        if (is_array($value)) {
            return true;
        }

        return is_string($value) && Str::contains($value, ',');
    }
}
