<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Enums;

enum Operator
{
    case and;
    case not;
    case or;
}
