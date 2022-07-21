<?php

namespace Wordless\Contracts\Abstraction\Composer;

use Composer\Installer\PackageEvent;
use Composer\Package\CompletePackage;
use Symfony\Component\Dotenv\Dotenv;
use Wordless\Exceptions\FailedToCopyFile;
use Wordless\Exceptions\FailedToCreateDirectory;
use Wordless\Exceptions\FailedToDeletePath;
use Wordless\Exceptions\FailedToGetDirectoryPermissions;
use Wordless\Exceptions\InvalidDirectory;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\DirectoryFiles;
use Wordless\Helpers\Environment;
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
        self::defineProjectRootConstant(
            dirname($composerEvent->getComposer()->getConfig()->get('vendor-dir'))
        );

        if (!self::isInfobaseWpThemePackage($composerPackage)) {
            return;
        }

        Environment::loadDotEnv();

        self::exportFilesToProjectRoot();

        passthru("php console theme:npm \"install\"");
    }

    private static function defineProjectRootConstant(string $path)
    {
        $root_project_path_const_name = 'ROOT_PROJECT_PATH';

        if (!defined($root_project_path_const_name)) {
            define($root_project_path_const_name, $path);
        }
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

    private static function isInfobaseWpThemePackage(CompletePackage $package): bool
    {
        return $package->getName() === 'infobaseit/infobase-wp-theme';
    }
}