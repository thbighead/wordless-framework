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

trait InfobaseAdvancedClassicEditor
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
    public static function setupAdvancedClassicEditor(PackageEvent $composerEvent)
    {
        $composerPackage = self::extractPackageFromEvent($composerEvent);

        if (!self::isInfobaseAdvancedClassicEditor($composerPackage)) {
            return;
        }

        self::exportAdvancedClassicEditorSetupFilesToProjectRoot(
            $composerEvent->getComposer()->getConfig()->get('vendor-dir'),
            $composerPackage->getName()
        );
    }

    private static function isInfobaseAdvancedClassicEditor(?CompletePackage $package): bool
    {
        return $package !== null && $package->getName() === 'infobaseit/advanced-classic-editor';
    }

    /**
     * @param string $vendor_path
     * @param string $package
     * @return string
     * @throws FailedToCopyFile
     * @throws FailedToCreateDirectory
     * @throws FailedToDeletePath
     * @throws FailedToGetDirectoryPermissions
     * @throws InvalidDirectory
     * @throws PathNotFoundException
     */
    private static function exportAdvancedClassicEditorSetupFilesToProjectRoot(string $vendor_path, string $package): string
    {
        $vendor_package_path = "$vendor_path/$package";
        $setup_path = "$vendor_package_path/setup";

        DirectoryFiles::recursiveCopy($setup_path, dirname($vendor_path));

        return $package;
    }
}
