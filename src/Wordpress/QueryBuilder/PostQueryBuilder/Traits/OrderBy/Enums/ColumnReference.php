<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\OrderBy\Enums;

enum ColumnReference: string
{
    case id = 'ID';
    case author = 'author';
    case title = 'title';
    case slug = 'name';
    case type = 'type';
    case date = 'date';
    case modified = 'modified';
    case parent = 'parent';
    case in_random_order = 'rand';
    case comment_count = 'comment_count';
    case search_relevance = 'relevance';
    case admin_menu_custom_order = 'menu_order';
    case meta_value = 'meta_value';
    case same_as_post_in = 'post__in';
    case same_as_post_name_in = 'post_name__in';
    case same_as_post_parent_in = 'post_parent__in';
}
