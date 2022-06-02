<?php

namespace Wordless\Abstractions;

use Composer\Script\Event;

class Composer
{
    public static function activatePlugin(Event $composerEvent)
    {
        dump($composerEvent);
    }

    public static function deactivatePlugin(Event $composerEvent)
    {
        dump($composerEvent);
    }
}