<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums;

enum Key: string
{
    case key_compare = 'compare';
    case key_meta_key = 'key';
    case key_meta_query = 'meta_query';
    case key_meta_value = 'value';
    case key_relation = 'relation';
    case key_value_type = 'type';
    case meta_prefix = 'meta_';
    case zero_value_key = '_wp_zero_value';
}
