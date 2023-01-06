<?php

namespace Wordless\Contracts\Abstraction\Composer;

use Composer\Installer\PackageEvent;
use Composer\IO\IOInterface;
use Composer\Script\Event;
use Wordless\Exceptions\CannotInitializeComposerIo;

trait InputOutput
{
    protected static IOInterface $composerIo;

    /**
     * @param PackageEvent|Event $event
     * @return void
     * @throws CannotInitializeComposerIo
     */
    protected static function initializeIo($event)
    {
        switch (true) {
            case $event instanceof Event:
            case $event instanceof PackageEvent:
                static::$composerIo = $event->getIO();
                break;
            default:
                throw new CannotInitializeComposerIo($event);
        }
    }

    protected static function getIo(): IOInterface
    {
        return static::$composerIo;
    }
}
