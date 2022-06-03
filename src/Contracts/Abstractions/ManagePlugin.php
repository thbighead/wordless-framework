<?php

namespace Wordless\Contracts\Abstractions;

use Composer\DependencyResolver\Operation\UninstallOperation;
use Composer\Installer\PackageEvent;
use Composer\Package\CompletePackage;
use Wordless\Helpers\Str;

trait ManagePlugin
{
    public static function activatePlugin(PackageEvent $composerEvent)
    {
        self::managePlugin($composerEvent, 'activate');
    }

    public static function deactivatePlugin(PackageEvent $composerEvent)
    {
        self::managePlugin($composerEvent, 'deactivate');
    }

    private static function extractPackageFromEvent(PackageEvent $composerEvent): CompletePackage
    {
        /** @var UninstallOperation $uninstallOperation */
        $uninstallOperation = $composerEvent->getOperation();
        /** @var CompletePackage $package */
        $package = $uninstallOperation->getPackage();

        return $package;
    }

    private static function isWpPluginPackage(CompletePackage $package): bool
    {
        return $package->getType() === 'wordpress-plugin';
    }

    private static function managePlugin(PackageEvent $composerEvent, string $plugin_command)
    {
        $package = self::extractPackageFromEvent($composerEvent);

        if (!self::isWpPluginPackage($package)) {
            return;
        }

        $plugin_name = Str::after($package->getName(), '/');

        passthru("php console wp:run \"plugin $plugin_command $plugin_name\"");
    }
}