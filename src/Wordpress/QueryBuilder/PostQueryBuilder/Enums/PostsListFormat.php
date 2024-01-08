<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Enums;

enum PostsListFormat: string
{
    final public const FIELDS_KEY = 'fields';

    case all_fields = 'all';
    case only_ids = 'ids';
    case parents_keyed_by_child_ids = 'id=>parent';
}
