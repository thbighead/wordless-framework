<?php declare(strict_types=1);

namespace Wordless\Wordpress\Enums;

enum StartOfWeek: int
{
    final public const KEY = 'start_of_week';
    case sunday = 0;
    case monday = 1;
    case tuesday = 2;
    case wednesday = 3;
    case thursday = 4;
    case friday = 5;
    case saturday = 6;
}
