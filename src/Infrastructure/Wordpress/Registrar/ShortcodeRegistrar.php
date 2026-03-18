<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Registrar;

use Wordless\Infrastructure\Wordpress\Registrar;

abstract class ShortcodeRegistrar implements Registrar
{
    abstract public static function mountHtml(array $attributes = [], ?string $content = null): string;

    abstract public static function tag(): string;

    public static function register(): void
    {
        add_shortcode(static::tag(), [static::class, 'mountHtml']);
    }
}
