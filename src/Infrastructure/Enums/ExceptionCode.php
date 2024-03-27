<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Enums;

enum ExceptionCode: int
{
    case caught_internally = 0;
    case development_error = 1;
    case logic_control = 2;
    case intentional_interrupt = 3;
}
