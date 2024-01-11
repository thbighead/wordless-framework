<?php declare(strict_types=1);

namespace Wordless\Application\Commands\RunTests\Traits\OutputOption\Enums;

enum TestOutput: string
{
    case regular = 'regular';
    case teamcity = 'teamcity';
    case testdox = 'testdox';
}
