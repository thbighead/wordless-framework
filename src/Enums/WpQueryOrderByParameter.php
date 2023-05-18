<?php

namespace Wordless\Enums;

class WpQueryOrderByParameter
{
    public const ID = 'ID';
    public const AUTHOR = 'author';
    public const TITLE = 'title';
    public const NAME = 'name';
    public const TYPE = 'type';
    public const DATE = 'date';
    public const MODIFIED = 'modified';
    public const PARENT = 'parent';
    public const IN_RANDOM_ORDER = 'rand';
    public const COMMENT_COUNT = 'comment_count';
    public const SEARCH_RELEVANCE = 'relevance';
    public const ADMIN_MENU_CUSTOM_ORDER = 'menu_order';
    public const META_VALUE = 'meta_value';
    public const SAME_AS_POST_IN = 'post__in';
    public const SAME_AS_POST_NAME_IN = 'post_name__in';
    public const SAME_AS_POST_PARENT_IN = 'post_parent__in';
}
