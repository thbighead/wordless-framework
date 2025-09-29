<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Role\Enums;

enum StandardRole: string
{
    case admin = 'administrator';
    case author = 'author';
    case contributor = 'contributor';
    case editor = 'editor';
    case subscriber = 'subscriber';
}
