<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress;

use Wordless\Application\Libraries\TypeBackedEnum\StringBackedEnum;
use Wordless\Infrastructure\Wordpress\Hook\Enums\Type;

interface Hook extends StringBackedEnum
{
    public function dispatch(mixed ...$arguments);
    public function type(): Type;
}
