<?php

namespace Wordless\Infrastructure;

abstract class Menu
{
    abstract public static function id(): string;

    abstract public static function name(): string;
}
