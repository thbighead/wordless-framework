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

        dd($vars);
    }

    public static function dump(...$vars)
    {
        if (!in_array(PHP_SAPI, ['cli', 'phpdbg'], true) && !headers_sent()) {
            header('Content-Type: text/html');
        }

        dump($vars);
    }
}