<?php

namespace Wordless\Enums;

enum ExceptionCode: int
{
    case caught_internally = 0;
    case development_error = 1;
    case logic_control = 2;
    case intentional_interrupt = 3;
}
