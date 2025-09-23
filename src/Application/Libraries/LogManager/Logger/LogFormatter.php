<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\LogManager\Logger;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use RuntimeException;
use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\Config\Traits\Internal\Exceptions\FailedToLoadConfigFile;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Libraries\LogManager\Logger;

class LogFormatter extends StreamHandler
{
    final public const CONFIG_KEY_DATETIME_FORMAT = 'datetime_format';
    final public const CONFIG_KEY_LINE_FORMAT = 'line_format';
    private const DEFAULT_DATETIME_FORMAT = 'd-M-Y H:m:s';
    private const DEFAULT_LINE_FORMAT = '[%datetime%] %channel%.%level_name%: %message% %context% %extra%';

    /**
     * @return LineFormatter
     * @throws EmptyConfigKey
     * @throws FailedToLoadConfigFile
     */
    public static function mountOutputFormatter(): LineFormatter
    {
        $config = Config::wordless()->ofKey(Logger::CONFIG_KEY_LOG);

        return new LineFormatter(
            "{$config->get(self::CONFIG_KEY_LINE_FORMAT, self::DEFAULT_LINE_FORMAT)}\n",
            $config->get(self::CONFIG_KEY_DATETIME_FORMAT, self::DEFAULT_DATETIME_FORMAT),
            false,
            true
        );
    }
}
