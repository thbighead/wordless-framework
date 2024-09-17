<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Wordless\Core\Composer;

use Composer\Composer;
use Composer\DependencyResolver\Operation\InstallOperation;
use Composer\DependencyResolver\Operation\UninstallOperation;
use Composer\DependencyResolver\Operation\UpdateOperation;
use Composer\InstalledVersions;
use Composer\Installer\PackageEvent;
use Composer\Package\CompletePackage;
use Composer\Package\RootPackage;
use Composer\Script\Event;
use OutOfBoundsException;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Core\Composer\Traits\InputOutput;
use Wordless\Core\Composer\Traits\ManagePlugin;
use Wordless\Core\Composer\Traits\PackageDiscovery;
use Wordless\Core\Composer\Traits\SetHostFromNginx;

class Main
{
    use InputOutput;
    use ManagePlugin;
    use PackageDiscovery;
    use SetHostFromNginx;

    /**
     * @return string
     * @throws OutOfBoundsException
     */
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
    public static function saveInstalledVersion(Event $composerEvent): void
    {
        static::initializeIo($composerEvent);
        $composer = $composerEvent->getComposer();
        /** @var RootPackage $projectPackage */
        $projectPackage = $composer->getPackage();

        self::defineProjectPath($composer);

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

    final protected static function defineProjectPath(Composer $composer): void
    {
        $root_project_path_constant = 'ROOT_PROJECT_PATH';

        if (!defined($root_project_path_constant)) {
            static::getIo()->write(
                "Defining $root_project_path_constant as {$composer->getConfig()->get('vendor-dir')}/.."
            );
            define(
                $root_project_path_constant,
                "{$composer->getConfig()->get('vendor-dir')}/.."
            );
        }
        static::getIo()->write("$root_project_path_constant defined as " . ROOT_PROJECT_PATH);
    }

    final protected static function extractPackageFromEvent(PackageEvent $composerEvent): ?CompletePackage
    {
        $operation = $composerEvent->getOperation();

        if (!($operation instanceof UninstallOperation
            || $operation instanceof InstallOperation
            || $operation instanceof UpdateOperation)) {
            return null;
        }

        /** @var CompletePackage $package */
        $package = $operation instanceof UpdateOperation ?
            $operation->getTargetPackage() : $operation->getPackage();

        return $package;
    }
}
