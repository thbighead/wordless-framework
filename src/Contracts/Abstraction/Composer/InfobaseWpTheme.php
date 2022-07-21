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
use Wordless\Helpers\ProjectPath;

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

        self::exportFilesToProjectRoot();

        passthru("php console theme:npm \"install\"");
    }

    private static function isInfobaseWpThemePackage(CompletePackage $package): bool
    {
        return $package->getName() === 'infobaseit/infobase-wp-theme';
    }

    /**
     * @return void
     * @throws FailedToCopyFile
     * @throws FailedToCreateDirectory
     * @throws FailedToGetDirectoryPermissions
     * @throws InvalidDirectory
     * @throws PathNotFoundException
     * @throws FailedToDeletePath
     */
    private static function exportFilesToProjectRoot()
    {
        DirectoryFiles::recursiveCopy($setup_path = ProjectPath::theme('setup'), ProjectPath::root());
        DirectoryFiles::recursiveDelete($setup_path);
    }
}