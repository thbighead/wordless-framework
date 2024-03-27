<?php declare(strict_types=1);

namespace Wordless\Application\Helpers;

use Wordless\Application\Helpers\Debugger\Traits\Internal;
use Wordless\Infrastructure\Helper;

class Debugger extends Helper
{
    use Internal;

    public static function calledFrom(): string
    {
        $where_it_was_called_info = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3)[2];
        $unknown_result = 'UNKNOWN_METHOD';
        $where_it_was_called = '';
        $class_key = 'class';
        $function_key = 'function';

        if (isset($where_it_was_called_info[$class_key])) {
            $where_it_was_called = "$where_it_was_called_info[$class_key]::";
        }

        return isset($where_it_was_called_info[$function_key]) ?
            "$where_it_was_called$where_it_was_called_info[$function_key]" :
            "$where_it_was_called$unknown_result";
    }

    public static function dd(mixed ...$vars): void
    {
        self::setContentTypeHeaderToTextHtml();

        dd(...$vars);
    }

    public static function dump(mixed ...$vars): void
    {
        self::setContentTypeHeaderToTextHtml();

        dump(...$vars);
    }

    public static function variableExport(mixed $variable): string
    {
        return var_export($variable, true);
    }
}
