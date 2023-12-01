<?php

namespace Wordless\Infrastructure\Wordpress\Listener;

use Wordless\Infrastructure\Wordpress\Listener;
use Wordless\Wordpress\Hook\Enums\Type;
use Wordless\Wordpress\Hook\Contracts\ActionHook;

abstract class ActionListener extends Listener
{
    abstract protected static function hook(): ActionHook;

    public static function hookIt(): void
    {
        add_action(
            static::hook()->value,
            [static::class, static::FUNCTION],
            static::priority(),
            static::functionNumberOfArgumentsAccepted()
        );
    }

    final protected static function type(): Type
    {
        return Type::action;
    }
}
