<?php

namespace Wordless\Helpers;

use const PHP_SAPI;

class Debugger
{
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

    public static function dd(...$vars)
    {
        self::setContentTypeHeaderToTextHtml();

        dd(...$vars);
    }

    public static function dump(...$vars)
    {
        self::setContentTypeHeaderToTextHtml();

        dump(...$vars);
    }

    public static function variableExport($variable): string
    {
        return var_export($variable, true);
    }

    private static function setContentTypeHeaderToTextHtml()
    {
        if (!in_array(PHP_SAPI, ['cli', 'phpdbg'], true) && !headers_sent()) {
            header('Content-Type: text/html');
        }
    }
}
