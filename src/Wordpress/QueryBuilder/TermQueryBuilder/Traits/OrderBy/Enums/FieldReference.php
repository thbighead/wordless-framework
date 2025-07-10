<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\TermQueryBuilder\Traits\OrderBy\Enums;

enum FieldReference: string
{
    case number_of_associated_objects = 'count';
    case term_description = 'description';
    case term_group = 'term_group';
    case term_id = 'term_id';
    case term_name = 'name';
    case term_order = 'term_order';
    case term_parent = 'parent';
    case term_slug = 'slug';
}
