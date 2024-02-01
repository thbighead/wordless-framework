<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Log\Adapters;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;

class LogFormatter extends StreamHandler
{
    public const SIMPLE_FORMAT = "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n";

    public static function mountOutputFormatter(): LineFormatter
    {
        return new LineFormatter(self::SIMPLE_FORMAT, 'd-M-Y H:m:s', false, true);
    }
}
