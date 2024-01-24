<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Enums;

enum Compare: string
{
    case between = 'BETWEEN';
    case in = 'IN';
    case not_between = 'NOT BETWEEN';
    case not_in = 'NOT IN';
}
