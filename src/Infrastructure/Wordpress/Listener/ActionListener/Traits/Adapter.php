<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Listener\ActionListener\Traits;

use Closure;
use Wordless\Wordpress\Hook\Enums\Type;

trait Adapter
{
    public static function hookIt(?Closure $callback = null): void
    {
        add_action(
            static::hook()->value,
            $callback ?? [static::class, static::FUNCTION],
            static::priority(),
            static::functionNumberOfArgumentsAccepted()
        );
    }

    final protected static function type(): Type
    {
        return Type::action;
    }
}
