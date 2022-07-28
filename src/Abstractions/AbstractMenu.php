<?php

namespace Wordless\Abstractions;

abstract class AbstractMenu
{
    abstract public static function id(): string;
    abstract public static function name(): string;
}