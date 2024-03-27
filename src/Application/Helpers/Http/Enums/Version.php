<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Http\Enums;

enum Version: string
{
    case http_1_0 = '1.0';
    case http_1_1 = '1.1';
}
