<?php declare(strict_types=1);

namespace Wordless\Application\Helpers;

use Wordless\Application\Helpers\Log\Enums\Type;
use Wordless\Application\Helpers\Log\Traits\Internal;

class Log
{
    use Internal;

    final public static function error(string $message): void
    {
        self::write($message, Type::ERROR);
    }

    final public static function info(string $message): void
    {
        self::write($message, Type::INFO);
    }

    final public static function warning(string $message): void
    {
        self::write($message, Type::WARNING);
    }
}
