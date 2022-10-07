<?php

namespace Wordless\Abstractions;

use Composer\DependencyResolver\Operation\InstallOperation;
use Composer\DependencyResolver\Operation\UninstallOperation;
use Composer\InstalledVersions;
use Composer\Installer\PackageEvent;
use Composer\Package\CompletePackage;
use Composer\Package\RootPackage;
use Composer\Script\Event;
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
     * @param Event $composerEvent
     * @return void
     * @throws PathNotFoundException
     */
    public static function saveInstalledVersion(Event $composerEvent)
    {
        /** @var RootPackage $projectPackage */
        $projectPackage = $composerEvent->getComposer()->getPackage();

        $root_project_path_constant = 'ROOT_PROJECT_PATH';
        if (!defined($root_project_path_constant)) {
            define(
                $root_project_path_constant,
                "{$composerEvent->getComposer()->getConfig()->get('vendor-dir')}/.."
            );
        }

        $style_css_path = ProjectPath::wpThemes('wordless/style.css');
        $style_css_content = file_get_contents($style_css_path);

        if (!Str::contains($style_css_content, 'Version:')) {
            file_put_contents(
                $style_css_path,
                str_replace(
                    $comment_closer = '*/',
                    "Version: {$projectPackage->getVersion()}" . PHP_EOL . $comment_closer,
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
