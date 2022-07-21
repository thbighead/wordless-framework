<?php

namespace Wordless\Contracts\Abstraction\Composer;

use Composer\Installer\PackageEvent;
use Composer\Package\CompletePackage;
use Wordless\Exceptions\FailedToCopyFile;
use Wordless\Exceptions\FailedToCreateDirectory;
use Wordless\Exceptions\FailedToDeletePath;
use Wordless\Exceptions\FailedToGetDirectoryPermissions;
use Wordless\Exceptions\InvalidDirectory;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\DirectoryFiles;
use Wordless\Helpers\Str;

trait InfobaseWpTheme
{
    /**
     * @param PackageEvent $composerEvent
     * @return void
     * @throws FailedToCopyFile
     * @throws FailedToCreateDirectory
     * @throws FailedToDeletePath
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

        $theme_path = self::exportFilesToProjectRoot(
            dirname($composerEvent->getComposer()->getConfig()->get('vendor-dir')),
            Str::after($composerPackage->getName(), '/')
        );

        passthru("cd $theme_path && npm install");
    }

    /**
     * @param string $root_path
     * @param string $theme_name
     * @return string
     * @throws FailedToCopyFile
     * @throws FailedToCreateDirectory
     * @throws FailedToDeletePath
     * @throws FailedToGetDirectoryPermissions
     * @throws InvalidDirectory
     * @throws PathNotFoundException
     */
    private static function exportFilesToProjectRoot(string $root_path, string $theme_name): string
    {
        $theme_path = "$root_path/wp/wp-content/themes/$theme_name";
        $setup_path = "$theme_path/setup";

        DirectoryFiles::recursiveCopy($setup_path, $root_path);
        DirectoryFiles::recursiveDelete($setup_path);

        return $theme_path;
    }

    private static function isInfobaseWpThemePackage(CompletePackage $package): bool
    {
        return $package->getName() === 'infobaseit/infobase-wp-theme';
    }
}