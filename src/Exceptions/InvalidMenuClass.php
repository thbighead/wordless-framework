<?php

namespace Wordless\Exceptions;

use Exception;
use Throwable;
use Wordless\Abstractions\AbstractMenu;

class InvalidMenuClass extends Exception
{
    public function __construct(string $menuClass, Throwable $previous = null)
    {
        parent::__construct("Class $menuClass isn't a " . AbstractMenu::class, 0, $previous);
    }
}