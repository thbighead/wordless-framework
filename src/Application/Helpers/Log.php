<?php declare(strict_types=1);

namespace Wordless\Application\Helpers;

use Monolog\Logger;
use Wordless\Application\Libraries\Log\Logger as WordlessLogger;

class Log
{
    private Logger $logger;

    public function __construct()
    {
        $this->logger = WordlessLogger::getInstance();
    }

    public static function alert($message, array $context = []): void
    {
        (new self)->writeLog(__FUNCTION__, $message, $context);
    }

    public static function critical($message, array $context = []): void
    {
        (new self)->writeLog(__FUNCTION__, $message, $context);
    }

    public static function debug($message, array $context = []): void
    {
        (new self)->writeLog(__FUNCTION__, $message, $context);
    }

    public static function emergency($message, array $context = []): void
    {
        (new self)->writeLog(__FUNCTION__, $message, $context);
    }

    public static function error($message, array $context = []): void
    {
        (new self)->writeLog(__FUNCTION__, $message, $context);
    }

    public static function info($message, array $context = []): void
    {
        (new self)->writeLog(__FUNCTION__, $message, $context);
    }

    public static function notice($message, array $context = []): void
    {
        (new self)->writeLog(__FUNCTION__, $message, $context);
    }

    public static function warning($message, array $context = []): void
    {
        (new self)->writeLog(__FUNCTION__, $message, $context);
    }

    protected function writeLog($level, $message, $context): void
    {
        $this->logger->{$level}(
            $message,
            $context
        );
    }
}
