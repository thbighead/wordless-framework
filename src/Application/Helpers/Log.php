<?php

namespace Wordless\Application\Helpers;

use Wordless\Application\Helpers\Log\Enums\Type;

class Log
{
    final public static function error(string $message): void
    {
        self::write($message, Type::ERROR);
    }

    final public static function info(string $message): void
    {
        self::write($message, Type::INFO);
    }

    final public static function warning(string $message): void
    {
        self::write($message, Type::WARNING);
    }

    private static function write(string $message, ?Type $type = null): void
    {
        if ($type !== null) {
            $type = Str::upper($type->value);
            $message = "[$type] $message";
        }

        error_log($message);
    }
}
