<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Log\Enums;

enum Type: string
{
    case ERROR = 'ERROR';
    case WARNING = 'WARNING';
    case INFO = 'INFO';
}
