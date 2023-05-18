<?php

namespace Wordless\Exceptions;

use Exception;
use Throwable;
use Wordless\Infrastructure\Menu;

class InvalidMenuClass extends Exception
{
    public function __construct(string $menuClass, Throwable $previous = null)
    {
        parent::__construct("Class $menuClass isn't a " . Menu::class, 0, $previous);
    }
}
