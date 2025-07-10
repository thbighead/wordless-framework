<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\TermQueryBuilder\Enums;

enum TermsListFormat: string
{
    public const FIELDS_KEY = 'fields';

    case wp_terms = 'all';
    case wp_terms_with_object_id_magic_property = 'all_with_object_id';
    case number_of_associated_objects = 'count';
    case only_parent_ids_keyed_by_term_ids = 'id=>parent';
    case only_taxonomy_term_ids = 'tt_ids';
    case only_term_ids = 'ids';
    case only_term_names = 'names';
    case only_term_names_keyed_by_term_ids = 'id=>name';
    case only_term_slugs = 'slugs';
    case only_term_slugs_keyed_by_term_ids = 'id=>slug';
}
