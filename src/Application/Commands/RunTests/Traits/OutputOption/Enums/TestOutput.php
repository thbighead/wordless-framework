<?php

namespace Wordless\Application\Commands\RunTests\Traits\OutputOption\Enums;

enum TestOutput: string
{
    case regular = 'regular';
    case teamcity = 'teamcity';
    case testdox = 'testdox';
}
