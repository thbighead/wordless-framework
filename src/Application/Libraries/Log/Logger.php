<?php

namespace Wordless\Application\Libraries\Log;

use Monolog\Logger as MonologLogger;
use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\Log\Adapters\LogFormatter;
use Wordless\Application\Helpers\Log\Adapters\RotatingFileHandler;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;

class Logger
{
    private const LOG_PATH = 'debug.log';
    private const MAX_LOG_FILES_LIMIT = 30;
    private string $getFullTimedPathName;
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
        $logger = new MonologLogger(Config::get('wordless.log.filename', 'wordless'));

        $handler = new RotatingFileHandler(
            $this->resolveFilePath(),
            (int)Config::get('wordless.log.wordless_line_prefix', self::MAX_LOG_FILES_LIMIT)
        );
        $handler->setFormatter(LogFormatter::mountOutputFormatter());

        $this->getFullTimedPathName = $handler->getTimedFilename();

        $logger->pushHandler($handler);
        self::$logger = $logger;
    }

    public static function getFullTimedPathName(): string
    {
        return (new self)->getFullTimedPathName;
    }

    public static function getInstance(): MonologLogger
    {
        return self::$logger;
    }

    /**
     * @return string
     * @throws PathNotFoundException
     */
    public function resolveFilePath(): string
    {
        return ProjectPath::wpContent('/logs')
            . Str::startWith(Config::get('wordless.log.wordless_line_prefix', self::LOG_PATH), '/');
    }
}
