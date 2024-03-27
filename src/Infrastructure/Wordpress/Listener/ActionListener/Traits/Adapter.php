<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Listener\ActionListener\Traits;

use Wordless\Wordpress\Hook\Enums\Type;

trait Adapter
{
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
