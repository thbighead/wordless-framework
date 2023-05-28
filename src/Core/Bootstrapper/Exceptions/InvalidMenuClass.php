<?php

namespace Wordless\Core\Bootstrapper\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use Wordless\Infrastructure\Wordpress\Menu;

class InvalidMenuClass extends ErrorException
{
    public function __construct(private readonly string $menuClass, ?Throwable $previous = null)
    {
        parent::__construct(
            "Class $this->menuClass isn't a " . Menu::class,
            ExceptionCode::development_error->value,
            previous: $previous
        );
    }
}
