<?php declare(strict_types=1);

namespace Wordless\Core\Composer\Traits;

use Composer\Installer\PackageEvent;
use Composer\Package\CompletePackage;
use RuntimeException;
use Wordless\Application\Helpers\Str;

trait ManagePlugin
{
    /**
     * @param PackageEvent $composerEvent
     * @return void
     * @throws RuntimeException
     */
    public static function activatePlugin(PackageEvent $composerEvent): void
    {
        self::managePlugin($composerEvent, 'activate');
    }

    /**
     * @param PackageEvent $composerEvent
     * @return void
     * @throws RuntimeException
     */
    public static function deactivatePlugin(PackageEvent $composerEvent): void
    {
        self::managePlugin($composerEvent, 'deactivate');
    }

    final protected static function isWpPluginPackage(CompletePackage $package): bool
    {
        return $package->getType() === 'wordpress-plugin';
    }

    /**
     * @param PackageEvent $composerEvent
     * @param string $plugin_command
     * @return void
     * @throws RuntimeException
     */
    private static function managePlugin(PackageEvent $composerEvent, string $plugin_command): void
    {
        static::initializeIo($composerEvent);

        $package = self::extractPackageFromEvent($composerEvent);

        if ($package === null || !self::isWpPluginPackage($package)) {
            static::getIo()->write('Not a Wordpress Plugin. Skipping.');
            return;
        }

        $plugin_name = Str::after($package->getName(), '/');
        static::getIo()->write(ucfirst($plugin_command) . " plugin '$plugin_name'.");
        $vendor_path = $composerEvent->getComposer()->getConfig()->get('vendor-dir');
        $autoload_path = "$vendor_path/autoload.php";

        if (is_file($autoload_path)) {
            require_once $autoload_path;

            exec('php console wp:run "db check" --quiet --no-tty', result_code: $db_checking);

            if ($db_checking) {
                static::getIo()->write('Database not ok. Skipping.');
                return;
            }

            passthru("php console wp:run \"plugin $plugin_command $plugin_name\" --no-tty");
        }
    }
}
