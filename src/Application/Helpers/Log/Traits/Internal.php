<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Log\Traits;

use Wordless\Application\Helpers\Log\Enums\Type;
use Wordless\Application\Helpers\Str;

trait Internal
{
    private static function write(string $message, ?Type $type = null): void
    {
        if ($type !== null) {
            $type = Str::upper($type->value);
            $message = "[$type] $message";
        }

        error_log($message);
    }
}
