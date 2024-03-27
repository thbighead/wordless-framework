<?php declare(strict_types=1);

namespace Wordless\Application\Helpers;

use Wordless\Application\Libraries\LogManager\Logger;
use Wordless\Infrastructure\Helper;

class Log extends Helper
{
    public static function alert($message, array $context = []): void
    {
        Logger::getInstance()->writeLog(__FUNCTION__, $message, $context);
    }

    public static function critical($message, array $context = []): void
    {
        Logger::getInstance()->writeLog(__FUNCTION__, $message, $context);
    }

    public static function debug($message, array $context = []): void
    {
        Logger::getInstance()->writeLog(__FUNCTION__, $message, $context);
    }

    public static function emergency($message, array $context = []): void
    {
        Logger::getInstance()->writeLog(__FUNCTION__, $message, $context);
    }

    public static function error($message, array $context = []): void
    {
        Logger::getInstance()->writeLog(__FUNCTION__, $message, $context);
    }

    public static function info($message, array $context = []): void
    {
        Logger::getInstance()->writeLog(__FUNCTION__, $message, $context);
    }

    public static function notice($message, array $context = []): void
    {
        Logger::getInstance()->writeLog(__FUNCTION__, $message, $context);
    }

    public static function warning($message, array $context = []): void
    {
        Logger::getInstance()->writeLog(__FUNCTION__, $message, $context);
    }
}
