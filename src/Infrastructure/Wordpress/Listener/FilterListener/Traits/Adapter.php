<?php

namespace Wordless\Infrastructure\Wordpress\Listener\FilterListener\Traits;

use Wordless\Wordpress\Hook\Enums\Type;

trait Adapter
{
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
