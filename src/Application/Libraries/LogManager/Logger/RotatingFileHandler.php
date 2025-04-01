<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\LogManager\Logger;

use Monolog\Handler\RotatingFileHandler as MonologRotatingFileHandler;
use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\DirectoryFiles;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToCreateDirectory;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToGetDirectoryPermissions;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Libraries\LogManager\Logger;

class RotatingFileHandler extends MonologRotatingFileHandler
{
    private const DEFAULT_FILENAME = 'debug.log';
    private const MAX_LOG_FILES_LIMIT = 30;

    private readonly ConfigSubjectDTO $config;

    /**
     * @throws EmptyConfigKey
     * @throws FailedToCreateDirectory
     * @throws FailedToGetDirectoryPermissions
     * @throws PathNotFoundException
     */
    public function __construct()
    {
        $this->config = Config::wordless()->ofKey(Logger::CONFIG_KEY_LOG);

        parent::__construct(
            $this->resolveFilePath(),
            (int)$this->config->get(Logger::CONFIG_KEY_MAX_FILES_LIMIT, self::MAX_LOG_FILES_LIMIT)
        );

        $this->setFormatter(LogFormatter::mountOutputFormatter());
    }

    public function getTimeFormattedFilename(): string
    {
        return $this->getTimedFilename();
    }

    /**
     * @return string
     * @throws FailedToCreateDirectory
     * @throws FailedToGetDirectoryPermissions
     * @throws PathNotFoundException
     */
    private function resolveFilePath(): string
    {
        try {
            $logs_directory_path = ProjectPath::root('logs');
        } catch (PathNotFoundException $exception) {
            $logs_directory_path = $exception->path;
            DirectoryFiles::createDirectoryAt($logs_directory_path);
        }

        return $logs_directory_path . Str::startWith(
                $this->config->get(Logger::CONFIG_KEY_FILENAME, self::DEFAULT_FILENAME),
                '/'
            );
    }
}
