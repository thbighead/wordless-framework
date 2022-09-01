<?php

namespace Wordless\Contracts\Abstraction\Composer;

use Composer\Script\Event;

trait PackageDiscovery
{
    public static function discover(Event $event)
    {
        dump($event->getComposer()->getRepositoryManager()->getLocalRepository()->getPackages());
    }
}
