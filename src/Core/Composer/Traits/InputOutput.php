<?php

namespace Wordless\Core\Composer\Traits;

use Composer\Installer\PackageEvent;
use Composer\IO\IOInterface;
use Composer\Script\Event;

trait InputOutput
{
    protected static IOInterface $composerIo;

    /**
     * @param PackageEvent|Event $event
     * @return void
     */
    protected static function initializeIo(PackageEvent|Event $event): void
    {
        static::$composerIo = $event->getIO();
    }

    protected static function getIo(): IOInterface
    {
        return static::$composerIo;
    }
}
