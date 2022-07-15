<?php

namespace Wordless\Helpers;

use const PHP_SAPI;

class Debugger
{
    public static function dd(...$vars)
    {
        if (!in_array(PHP_SAPI, ['cli', 'phpdbg'], true) && !headers_sent()) {
            header('Content-Type: text/html');
        }

        dd(...$vars);
    }

    public static function dump(...$vars)
    {
        if (!in_array(PHP_SAPI, ['cli', 'phpdbg'], true) && !headers_sent()) {
            header('Content-Type: text/html');
        }

        dump(...$vars);
    }

    public static function variableExport($variable): string
    {
        $exported_variable = var_export($variable, true);

        return is_array($variable) ? self::beautifyArrayExport($exported_variable) : $exported_variable;
    }

    private static function beautifyArrayExport(string $exported_array): string
    {
        do {
            $exported_array = preg_replace(
                '/array \((.*)\)/s',
                '[$1]',
                $exported_array,
                -1,
                $count
            );
        } while ($count > 0);

        return $exported_array;
    }
}