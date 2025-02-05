<?php declare(strict_types=1);

namespace Wordless\Application\Commands\DistributeFront\Enums;

enum Type: string
{
    case css = 'css';
    case js = 'js';
}
