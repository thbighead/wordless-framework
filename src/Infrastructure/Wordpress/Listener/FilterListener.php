<?php

namespace Wordless\Infrastructure\Wordpress\Listener;

use Wordless\Infrastructure\Wordpress\Listener;
use Wordless\Wordpress\Hook\Contracts\FilterHook;
use Wordless\Wordpress\Hook\Enums\Type;

abstract class FilterListener extends Listener
{
    abstract protected static function hook(): FilterHook;

    public static function hookIt(): void
    {
        add_filter(
            static::hook()->value,
            [static::class, static::FUNCTION],
            static::priority(),
            static::functionNumberOfArgumentsAccepted()
        );
    }

    final protected static function type(): Type
    {
        return Type::filter;
    }
}
