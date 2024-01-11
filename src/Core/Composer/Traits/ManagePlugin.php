<?php declare(strict_types=1);

namespace Wordless\Core\Composer\Traits;

use Composer\Installer\PackageEvent;
use Composer\Package\CompletePackage;
use Wordless\Application\Helpers\Str;

trait ManagePlugin
{
    public static function activatePlugin(PackageEvent $composerEvent)
    {
        self::managePlugin($composerEvent, 'activate');
    }

    public static function deactivatePlugin(PackageEvent $composerEvent)
    {
        self::managePlugin($composerEvent, 'deactivate');
    }

    final protected static function isWpPluginPackage(CompletePackage $package): bool
    {
        return $package->getType() === 'wordpress-plugin';
    }

    private static function managePlugin(PackageEvent $composerEvent, string $plugin_command)
    {
        static::initializeIo($composerEvent);
        $package = self::extractPackageFromEvent($composerEvent);

        if ($package === null || !self::isWpPluginPackage($package)) {
            static::getIo()->write('Not a Wordpress Plugin. Skipping.');
            return;
        }

        $plugin_name = Str::after($package->getName(), '/');
        static::getIo()->write("Activating plugin '$plugin_name'.");
        $vendor_path = $composerEvent->getComposer()->getConfig()->get('vendor-dir');

        if (is_file("$vendor_path/autoload.php")) {
            passthru("php console wp:run \"plugin $plugin_command $plugin_name\"");
        }
    }
}
