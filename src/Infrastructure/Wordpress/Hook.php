<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress;

use Wordless\Application\Libraries\TypeBackedEnum\StringBackedEnum;

interface Hook extends StringBackedEnum
{
    public function dispatch(mixed ...$arguments);
}
