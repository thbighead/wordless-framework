<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Schedule\Enums;

enum Recurrence: string
{
    case daily = 'daily';
    case hourly = 'hourly';
    case twice_daily = 'twicedaily';
    case weekly = 'weekly';
}
