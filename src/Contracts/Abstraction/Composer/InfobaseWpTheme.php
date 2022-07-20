<?php

namespace Wordless\Contracts\Abstraction\Composer;

use Composer\Installer\PackageEvent;
use Composer\Package\CompletePackage;

trait InfobaseWpTheme
{
    public static function installInfobaseWpTheme(PackageEvent $composerEvent)
    {
        $composerPackage = self::extractPackageFromEvent($composerEvent);

        if (!self::isInfobaseWpThemePackage($composerPackage)) {
            return;
        }

        passthru("php console theme:npm \"install\"");
    }

    private static function isInfobaseWpThemePackage(CompletePackage $package): bool
    {
        return $package->getName() === 'infobaseit/infobase-wp-theme';
    }
}