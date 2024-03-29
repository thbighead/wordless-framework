<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress;

abstract class Menu
{
    abstract public static function id(): string;

    abstract public static function name(): string;
}
