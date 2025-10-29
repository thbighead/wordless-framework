<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Comment\Enums;

enum StandardType: string
{
    public const PINGS = 'pings';

    case comment = 'comment';
    case ping_back = 'pingback';
    case track_back = 'trackback';
}
