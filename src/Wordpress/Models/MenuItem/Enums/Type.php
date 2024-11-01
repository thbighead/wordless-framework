<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\MenuItem\Enums;

enum Type: string
{
    case custom = 'custom';
    case post_type = 'post_type';
    case post_type_archive = 'post_type_archive';
    case taxonomy = 'taxonomy';
}
