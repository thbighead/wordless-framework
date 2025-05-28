<?php declare(strict_types=1);

namespace Wordless\Application\Cachers;

use Wordless\Application\Helpers\Plugin;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Infrastructure\Cacher;

class PluginsCacher extends Cacher
{
    final public static function retrievePluginsList(): array
    {
        try {
            require_once ProjectPath::wpCore('wp-admin/includes/plugin.php');
        } catch (PathNotFoundException) {
            return [];
        }

        $list = [
            Plugin::TYPE_MUST_USE => get_mu_plugins(),
            Plugin::TYPE_NORMAL => get_plugins(),
        ];

        foreach ($list as $type => &$plugins) {
            foreach ($plugins as $plugin => &$plugin_data) {
                $plugin_data[Plugin::DATA_ID] = $plugin;
                $plugin_data[Plugin::DATA_IS_ACTIVE] = $type === Plugin::TYPE_MUST_USE || is_plugin_active($plugin);
            }
        }

        return $list;
    }

    protected function cacheFilename(): string
    {
        return 'plugins.php';
    }

    protected function mountCacheArray(): array
    {
        return self::retrievePluginsList();
    }
}
