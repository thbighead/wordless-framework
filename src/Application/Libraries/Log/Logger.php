<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\Log;

use Monolog\Logger as MonologLogger;
use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Libraries\DesignPattern\Singleton;
use Wordless\Application\Libraries\Log\Logger\LogFormatter;
use Wordless\Application\Libraries\Log\Logger\RotatingFileHandler;

class Logger extends Singleton
{
    public const CONFIG_FILENAME = 'filename';
    final public const CONFIG_KEY_LOG = 'log';
    final public const CONFIG_WORDLESS_LINE_PREFIX = 'wordless_line_prefix';
    final public const CONFIG_MAX_FILES_LIMIT = 'max_files_limit';
    private const DEFAULT_FILENAME = 'debug.log';
    private const MAX_LOG_FILES_LIMIT = 30;

    private string $getFullTimedPathName;
    private readonly ConfigSubjectDTO $config;
    private readonly MonologLogger $logger;

    public static function getFullTimedPathName(): string
    {
        return self::getInstance()->getFullTimedPathName;
    }

    /**
     * @return string
     * @throws PathNotFoundException
     */
    public function resolveFilePath(): string
    {
        return ProjectPath::wpContent('logs')
            . Str::startWith(
                $this->config->get(self::CONFIG_FILENAME, self::DEFAULT_FILENAME), '/'
            );
    }

    public function writeLog(string $level, string $message, array $context): void
    {
        $this->logger->{$level}($message, $context);
    }

    /**
     * @throws PathNotFoundException
     */
    protected function __construct()
    {
        parent::__construct();

        $this->config = Config::of('wordless.' . self::CONFIG_KEY_LOG);
        $this->logger = new MonologLogger(
            $this->config->get(self::CONFIG_WORDLESS_LINE_PREFIX, 'wordless')
        );
        $handler = new RotatingFileHandler(
            $this->resolveFilePath(),
            (int)$this->config->get(self::CONFIG_MAX_FILES_LIMIT, self::MAX_LOG_FILES_LIMIT)
        );

        $handler->setFormatter(LogFormatter::mountOutputFormatter());

        $this->getFullTimedPathName = $handler->getTimedFilename();

        $this->logger->pushHandler($handler);
    }
}
