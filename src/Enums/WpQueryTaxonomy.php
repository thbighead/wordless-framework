<?php

namespace Wordless\Enums;

class WpQueryTaxonomy
{
    public const COLUMN_NAME = 'name';
    public const COLUMN_SLUG = 'slug';
    public const COLUMN_TERM_TAXONOMY_ID = 'term_taxonomy_id';
    public const COLUMN_TERM_ID = 'term_id';
    public const KEY_COLUMN = 'field';
    public const KEY_INCLUDE_CHILDREN = 'include_children';
    public const KEY_NEST_LOGICAL_OPERATION = 'relation';
    public const KEY_OPERATOR = 'operator';
    public const KEY_RELATION = 'relation';
    public const KEY_TAXONOMY_QUERY = 'tax_query';
    public const KEY_TAXONOMY = 'taxonomy';
    public const KEY_TERMS = 'terms';
    public const OPERATOR_AND = 'AND';
    public const OPERATOR_EXISTS = 'EXISTS';
    public const OPERATOR_IN = 'IN';
    public const OPERATOR_NOT_EXISTS = 'NOT EXISTS';
    public const OPERATOR_NOT_IN = 'NOT IN';
    public const RELATION_AND = 'AND';
    public const RELATION_OR = 'OR';
}
