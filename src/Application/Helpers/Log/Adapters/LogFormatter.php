<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Log\Adapters;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;

class LogFormatter extends StreamHandler
{
    public const SIMPLE_FORMAT = "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n";
    private const DEFAULT_DATETIME_LINE_FORMAT = 'd-M-Y H:m:s';

    /**
     * @throws PathNotFoundException
     */
    public static function mountOutputFormatter(): LineFormatter
    {
        return new LineFormatter(
            self::SIMPLE_FORMAT,
            Config::get('wordless.log.file_line_datetime_format', self::DEFAULT_DATETIME_LINE_FORMAT),
            false,
            true
        );
    }
}
