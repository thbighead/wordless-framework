<?php

namespace Wordless\Abstractions;

use Composer\DependencyResolver\Operation\InstallOperation;
use Composer\DependencyResolver\Operation\UninstallOperation;
use Composer\Installer\PackageEvent;
use Composer\Package\CompletePackage;
use Wordless\Contracts\Abstraction\Composer\InfobaseWpTheme;
use Wordless\Contracts\Abstraction\Composer\ManagePlugin;

class Composer
{
    use InfobaseWpTheme, ManagePlugin;

    private static function extractPackageFromEvent(PackageEvent $composerEvent): CompletePackage
    {
        /** @var InstallOperation|UninstallOperation $operation */
        $operation = $composerEvent->getOperation();
        /** @var CompletePackage $package */
        $package = $operation->getPackage();

        return $package;
    }
}