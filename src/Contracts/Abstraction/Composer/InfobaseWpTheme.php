<?php

namespace Wordless\Contracts\Abstraction\Composer;

use Composer\Installer\PackageEvent;
use Composer\Package\CompletePackage;
use Wordless\Exceptions\FailedToCopyFile;
use Wordless\Exceptions\FailedToCreateDirectory;
use Wordless\Exceptions\FailedToGetDirectoryPermissions;
use Wordless\Exceptions\InvalidDirectory;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\DirectoryFiles;
use Wordless\Helpers\ProjectPath;
use Wordless\Helpers\Str;

trait InfobaseWpTheme
{
    /**
     * @param PackageEvent $composerEvent
     * @return void
     * @throws FailedToCopyFile
     * @throws FailedToCreateDirectory
     * @throws FailedToGetDirectoryPermissions
     * @throws InvalidDirectory
     * @throws PathNotFoundException
     */
    public static function installInfobaseWpTheme(PackageEvent $composerEvent)
    {
        $composerPackage = self::extractPackageFromEvent($composerEvent);

        if (!self::isInfobaseWpThemePackage($composerPackage)) {
            return;
        }

        $vendor_path = $composerEvent->getComposer()->getConfig()->get('vendor-dir');
        $package_full_name = $composerPackage->getName();

        $root_path = dirname($vendor_path);
        $theme_name = Str::after($package_full_name, '/');

        $theme_path = "$root_path/wp/wp-content/themes/$theme_name";

        if (!defined('ROOT_PROJECT_PATH')) {
            define('ROOT_PROJECT_PATH', __DIR__ . '/../../../..');
        }

        try {
            ProjectPath::wpThemes($theme_name);
        } catch (PathNotFoundException $exception) {
            self::exportFilesToProjectRoot(
                $root_path,
                $package_full_name,
                $theme_path,
                $vendor_path
            );
        }

        passthru("cd $theme_path && npm install");
    }

    /**
     * @param string $root_path
     * @param string $theme_name
     * @param string $wp_theme_path
     * @param string $vendor_path
     * @return string
     * @throws FailedToCopyFile
     * @throws FailedToCreateDirectory
     * @throws FailedToGetDirectoryPermissions
     * @throws InvalidDirectory
     * @throws PathNotFoundException
     */
    protected static function exportFilesToProjectRoot(
        string $root_path,
        string $theme_name,
        string $wp_theme_path,
        string $vendor_path
    ): string
    {
        $vendor_theme_path = "$vendor_path/$theme_name";
        $wp_path = "$vendor_theme_path/setup/wp";
        $extra_classes = "$vendor_theme_path/setup/packages";
        $setup_path = "$vendor_theme_path/setup";

        DirectoryFiles::recursiveCopy($vendor_theme_path, $wp_theme_path, [$setup_path]);
        DirectoryFiles::recursiveCopy($wp_path, $root_path."/wp");
        DirectoryFiles::recursiveCopy($extra_classes, "$root_path/packages");

        return $wp_theme_path;
    }

    protected static function isInfobaseWpThemePackage(CompletePackage $package): bool
    {
        return $package->getName() === 'infobaseit/infobase-wp-theme';
    }
}
