<?php

namespace Wordless\Abstractions;

use Composer\DependencyResolver\Operation\InstallOperation;
use Composer\DependencyResolver\Operation\UninstallOperation;
use Composer\InstalledVersions;
use Composer\Installer\PackageEvent;
use Composer\Package\CompletePackage;
use Wordless\Contracts\Abstraction\Composer\ManagePlugin;
use Wordless\Contracts\Abstraction\Composer\PackageDiscovery;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\ProjectPath;
use Wordless\Helpers\Str;

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

    /**
     * @param PackageEvent $composerEvent
     * @return void
     * @throws PathNotFoundException
     */
    public static function saveInstalledVersion(PackageEvent $composerEvent)
    {
        $package = self::extractPackageFromEvent($composerEvent);
        $style_css_path = ProjectPath::wpThemes('wordless/style.css');
        $style_css_content = file_get_contents($style_css_path);

        if (!Str::contains($style_css_content, 'Version:')) {
            file_put_contents(
                $style_css_path,
                str_replace(
                    '*/',
                    PHP_EOL . "Version: {$package->getVersion()}" . PHP_EOL . '*/',
                    $style_css_content
                )
            );
        }
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
