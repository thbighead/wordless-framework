<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Repository\Enums;

enum Field
{
    case name;
    case slug;
    case term_id;
    case term_taxonomy_id;
}
