<?php

namespace Wordless\Contracts\Abstraction\Composer;

use Composer\Package\CompletePackage;
use Composer\Script\Event;

trait PackageDiscovery
{
    public static function discover(Event $event)
    {
        /** @var CompletePackage[] $packagesList */
        $packagesList = $event->getComposer()->getRepositoryManager()->getLocalRepository()->getPackages();

        foreach ($packagesList as $package) {
            dump($package->getExtra());
        }
    }
}
