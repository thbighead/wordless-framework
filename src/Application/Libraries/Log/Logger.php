<?php

namespace Wordless\Application\Libraries\Log;

use Monolog\Logger as MonologLogger;
use Wordless\Application\Helpers\Environment;
use Wordless\Application\Helpers\Log\Adapters\LogFormatter;
use Wordless\Application\Helpers\Log\Adapters\RotatingFileHandler;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;

class Logger
{
    private const LOG_PATH = 'debug.log';
    private static string $getFullTimedPathName;
    private static MonologLogger $logger;

    /**
     * @throws PathNotFoundException
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * @throws PathNotFoundException
     */
    public function init(): void
    {
        $logger = new MonologLogger(
            Environment::get('APP_NAME', 'wordless')
            . '.'
            . Environment::get('APP_ENV')
        );

        $handler = new RotatingFileHandler(
            ProjectPath::wpContent('/logs') . self::LOG_PATH,
            30
        );
        $handler->setFormatter(LogFormatter::mountOutputFormatter());

        self::$getFullTimedPathName = $handler->getTimedFilename();

        $logger->pushHandler($handler);

        self::$logger = $logger;
    }

    public static function getFullTimedPathName(): string
    {
        return self::$getFullTimedPathName;
    }

    public static function getInstance(): MonologLogger
    {
        return self::$logger;
    }
}
