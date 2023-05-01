<?php

namespace Wordless\Helpers;

use Exception;

class Log
{
    public const TYPE_ERROR = 'ERROR';
    public const TYPE_WARNING = 'WARNING';
    public const TYPE_INFO = 'INFO';

    public static function error(string $message)
    {
        self::write($message, self::TYPE_ERROR);
    }

    public static function impossibleException(Exception $exception)
    {
        self::error("This is impossible, but... {$exception->getMessage()}");
    }

    public static function info(string $message)
    {
        self::write($message, self::TYPE_INFO);
    }

    public static function warning(string $message)
    {
        self::write($message, self::TYPE_WARNING);
    }

    private static function write(string $message, ?string $type = null)
    {
        if ($type !== null) {
            $type = Str::upper($type);
            $message = "[$type] $message";
        }

        error_log($message);
    }
}
