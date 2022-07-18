<?php

namespace Wordless\Contracts\Abstraction\Composer;

use Composer\DependencyResolver\Operation\UninstallOperation;
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

        // TODO: mover os arquivos como bem quiser para intalar o tema como bem entender
    }

    private static function extractPackageFromEvent(PackageEvent $composerEvent): CompletePackage
    {
        /** @var UninstallOperation $operation */
        $operation = $composerEvent->getOperation();
        /** @var CompletePackage $package */
        $package = $operation->getPackage();

        return $package;
    }

    private static function isInfobaseWpThemePackage(CompletePackage $package): bool
    {
        // TODO: testar se a função getName retorna o nome do pacote no formato vendor/package para comparação
        return $package->getName() === 'infobaseit/infobase-wp-theme';
    }
}