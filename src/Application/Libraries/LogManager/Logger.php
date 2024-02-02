<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\LogManager;

use Monolog\Logger as MonologLogger;
use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Libraries\DesignPattern\Singleton;
use Wordless\Application\Libraries\LogManager\Logger\RotatingFileHandler;

class Logger extends Singleton
{
    final public const CONFIG_KEY_FILENAME = 'filename';
    final public const CONFIG_KEY_LOG = 'log';
    final public const CONFIG_KEY_WORDLESS_LINE_PREFIX = 'wordless_line_prefix';
    final public const CONFIG_KEY_MAX_FILES_LIMIT = 'max_files_limit';

    private string $getFullTimedPathName;
    private readonly ConfigSubjectDTO $config;
    private readonly MonologLogger $logger;

    public static function getFullTimedPathName(): string
    {
        return self::getInstance()->getFullTimedPathName;
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
            $this->config->get(self::CONFIG_KEY_WORDLESS_LINE_PREFIX, 'wordless')
        );
        $handler = new RotatingFileHandler;

        $this->getFullTimedPathName = $handler->getTimeFormattedFilename();

        $this->logger->pushHandler($handler);
    }
}
