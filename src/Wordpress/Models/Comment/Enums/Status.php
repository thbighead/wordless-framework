<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Comment\Enums;

enum Status: string
{
    case approve = '1';
    case hold = '0';
    case spam = 'spam';
    case trash = 'trash';
}
