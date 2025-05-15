<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Schedule\Contracts;

use Wordless\Application\Libraries\TypeBackedEnum\StringBackedEnum;

interface RecurrenceInSeconds extends StringBackedEnum
{
    public function intervalInSeconds(): int;

    public function displayName(): string;
}
