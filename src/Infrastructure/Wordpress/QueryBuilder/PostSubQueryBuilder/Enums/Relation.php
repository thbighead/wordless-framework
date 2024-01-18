<?php

namespace Wordless\Infrastructure\Wordpress\QueryBuilder\PostSubQueryBuilder\Enums;

enum Relation
{
    public const KEY = 'relation';

    case and;
    case or;
}
