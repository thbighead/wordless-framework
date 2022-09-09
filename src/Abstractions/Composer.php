<?php

namespace Wordless\Abstractions;

use Composer\DependencyResolver\Operation\InstallOperation;
use Composer\DependencyResolver\Operation\UninstallOperation;
use Composer\InstalledVersions;
use Composer\Installer\PackageEvent;
use Composer\Package\CompletePackage;
use Wordless\Contracts\Abstraction\Composer\ManagePlugin;
use Wordless\Contracts\Abstraction\Composer\PackageDiscovery;
use Wordless\Helpers\ProjectPath;

class Composer
{
    use ManagePlugin, PackageDiscovery;

    private const WORDLESS_EXTRA_KEY = 'wordless';

    public static function getFrameworkInstalledVersion(): string
    {
        return InstalledVersions::getVersion(ProjectPath::VENDOR_PACKAGE_RELATIVE_PATH);
    }

    public static function isPackageInstalled(string $package_full_name): bool
    {
        return InstalledVersions::isInstalled($package_full_name);
    }

    private static function extractPackageFromEvent(PackageEvent $composerEvent): ?CompletePackage
    {
        $operation = $composerEvent->getOperation();

        if (!($operation instanceof UninstallOperation || $operation instanceof InstallOperation)) {
            return null;
        }

        /** @var CompletePackage $package */
        $package = $operation->getPackage();

        return $package;
    }
}
