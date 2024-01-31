<?php declare(strict_types=1);

namespace Wordless\Application\Helpers;

use Monolog\Logger;
use Wordless\Application\Helpers\Log\Adapters\LogFormatter;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;

class Log
{
    private Logger $logger;
    private const LOG_PATH = 'test.log';

    public function __construct()
    {
        $this->logger = new Logger(
            Environment::get('APP_NAME', 'wordless')
            . '.'
            . Environment::get('APP_ENV')
        );

        try {
            $path = ProjectPath::wpContent(self::LOG_PATH);
        } catch (PathNotFoundException $exception) {
            DirectoryFiles::createFileAt($path = $exception->getPath());
        }

        $this->logger->pushHandler(new LogFormatter($path));
        $this->logger->pushHandler(new LogFormatter($path));
    }

    public static function alert($message, array $context = [], bool $json_format = false): void
    {
        (new self)->writeLog(__FUNCTION__, $message, $context, $json_format);
    }

    public static function critical($message, array $context = [], bool $json_format = false): void
    {
        (new self)->writeLog(__FUNCTION__, $message, $context, $json_format);
    }

    public static function debug($message, array $context = [], bool $json_format = false): void
    {
        (new self)->writeLog(__FUNCTION__, $message, $context, $json_format);
    }

    public static function emergency($message, array $context = [], bool $json_format = false): void
    {
        (new self)->writeLog(__FUNCTION__, $message, $context, $json_format);
    }

    public static function error($message, array $context = [], bool $json_format = false): void
    {
        (new self)->writeLog(__FUNCTION__, $message, $context, $json_format);
    }

    public static function info($message, array $context = [], bool $json_format = false): void
    {
        (new self)->writeLog(__FUNCTION__, $message, $context, $json_format);
    }

    public static function notice($message, array $context = [], bool $json_format = false): void
    {
        (new self)->writeLog(__FUNCTION__, $message, $context, $json_format);
    }

    public static function warning($message, array $context = [], bool $json_format = false): void
    {
        (new self)->writeLog(__FUNCTION__, $message, $context, $json_format);
    }

    protected function formatMessage($message, bool $json_format = false): false|string|null
    {
        if (is_array($message)) {
            return var_export($message, true);
        } elseif ($json_format === true || is_object($message)) {
            return json_encode($message);
        }

        return (string)$message;
    }

    protected function writeLog($level, $message, $context, bool $json_format): void
    {
        $this->logger->{$level}(
            $this->formatMessage($message, $json_format),
            $context
        );
    }
}
