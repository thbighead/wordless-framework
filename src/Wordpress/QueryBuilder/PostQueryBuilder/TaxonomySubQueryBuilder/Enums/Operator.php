<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Enums;

enum Operator: string
{
    case and = 'AND';
    case exists = 'EXISTS';
    case in = 'IN';
    case not_exists = 'NOT EXISTS';
    case not_in = 'NOT IN';
}
