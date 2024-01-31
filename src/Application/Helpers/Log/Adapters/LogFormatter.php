<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Log\Adapters;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;

class LogFormatter extends StreamHandler
{

    public const SIMPLE_FORMAT = "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n";

    public function __construct($stream, int|string|Level $level = Level::Debug, bool $bubble = true, ?int $filePermission = null, bool $useLocking = false)
    {
        parent::__construct($stream, $level, $bubble, $filePermission, $useLocking);
        $this->setFormatter($this->mountOutputFormatter());
    }

    private function mountOutputFormatter(): LineFormatter
    {
        return new LineFormatter(self::SIMPLE_FORMAT, 'd-M-Y H:m:s', false, true);
    }
}
