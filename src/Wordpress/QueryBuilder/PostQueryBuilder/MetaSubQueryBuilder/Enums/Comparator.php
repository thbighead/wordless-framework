<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums;

use Wordless\Wordpress\QueryBuilder\Enums\Operator;

enum Comparator: string
{
    case compare_different = Operator::different->value;
    case compare_between = 'BETWEEN';
    case compare_equal = Operator::equal->value;
    case compare_exists = 'EXISTS';
    case compare_greater_than = Operator::greater_than->value;
    case compare_greater_than_or_equal = Operator::greater_than_or_equal->value;
    case compare_in = 'IN';
    case compare_less_than = Operator::less_than->value;
    case compare_less_than_or_equal = Operator::less_than_or_equal->value;
    case compare_like = Operator::like->value;
    case compare_not_between = 'NOT BETWEEN';
    case compare_not_exists = 'NOT EXISTS';
    case compare_not_like = 'NOT LIKE';
    case compare_not_in = 'NOT IN';
    case compare_regex = 'REGEXP';
    case compare_like_regex = 'RLIKE';
}
