<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress;

use Wordless\Wordpress\Hook\Enums\Type;

abstract class Listener
{
    abstract protected static function hook(): Hook;

    abstract protected static function type(): Type;

    /**
     * The public static method which shall be executed during hook.
     */
    protected const FUNCTION = 'register';

    public static function hookIt(): void
    {
        $hook_addition_function = 'add_' . static::type()->name;
        $hook_addition_function(
            static::hook()->value,
            [static::class, static::FUNCTION],
            static::priority(),
            abs(static::functionNumberOfArgumentsAccepted())
        );
    }

    public static function priority(): int
    {
        return 10;
    }

    protected static function functionNumberOfArgumentsAccepted(): int
    {
        return 0;
    }
}
