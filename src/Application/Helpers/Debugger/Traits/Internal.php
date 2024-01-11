<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Debugger\Traits;

trait Internal
{
    private static function setContentTypeHeaderToTextHtml(): void
    {
        if (!in_array(PHP_SAPI, ['cli', 'phpdbg'], true) && !headers_sent()) {
            header('Content-Type: text/html');
        }
    }
}
