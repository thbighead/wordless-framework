<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress;

interface Registrar
{
    public static function register(): void;
}
