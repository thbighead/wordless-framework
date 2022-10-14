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

trait ManagePlugin
{
    /**
     * @throws FailedToCreateDirectory
     * @throws FailedToGetDirectoryPermissions
     * @throws InvalidDirectory
     * @throws FailedToCopyFile
     * @throws FailedToDeletePath
     * @throws PathNotFoundException
     */
    public static function activatePlugin(PackageEvent $composerEvent)
    {
        self::managePlugin($composerEvent, 'activate');
        self::setupAcfPro($composerEvent);
    }

    public static function deactivatePlugin(PackageEvent $composerEvent)
    {
        self::managePlugin($composerEvent, 'deactivate');
    }

    private static function isWpPluginPackage(CompletePackage $package): bool
    {
        return $package->getType() === 'wordpress-plugin';
    }

    private static function managePlugin(PackageEvent $composerEvent, string $plugin_command)
    {
        $package = self::extractPackageFromEvent($composerEvent);

        if ($package === null || !self::isWpPluginPackage($package)) {
            return;
        }

        $plugin_name = Str::after($package->getName(), '/');
        $vendor_path = $composerEvent->getComposer()->getConfig()->get('vendor-dir');

        if (is_file("$vendor_path/autoload.php")) {
            passthru("php console wp:run \"plugin $plugin_command $plugin_name\"");
        }
    }

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
    public static function setupAcfPro(PackageEvent $composerEvent)
    {
        $composerPackage = self::extractPackageFromEvent($composerEvent);

        if (!self::isInfobaseAcfProPlugin($composerPackage)) {
            return;
        }

        self::exportAcfSetupFilesToProjectRoot(
            dirname($composerEvent->getComposer()->getConfig()->get('vendor-dir')),
            Str::after($composerPackage->getName(), '/')
        );
    }

    /**
     * @param string $root_path
     * @param string $plugin_name
     * @return string
     * @throws FailedToCopyFile
     * @throws FailedToCreateDirectory
     * @throws FailedToDeletePath
     * @throws FailedToGetDirectoryPermissions
     * @throws InvalidDirectory
     * @throws PathNotFoundException
     */
    private static function exportAcfSetupFilesToProjectRoot(string $root_path, string $plugin_name): string
    {
        $plugin_path = "$root_path/wp/wp-content/plugins/$plugin_name";
        $setup_path = "$plugin_path/setup";

        DirectoryFiles::recursiveCopy($setup_path, $root_path);
        DirectoryFiles::recursiveDelete($setup_path);

        return $plugin_path;
    }

    private static function isInfobaseAcfProPlugin(?CompletePackage $package): bool
    {
        return  $package !== null && $package->getName() === 'infobaseit/acf-pro';
    }
}
