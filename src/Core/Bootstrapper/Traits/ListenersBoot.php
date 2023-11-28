<?php

namespace Wordless\Core\Bootstrapper\Traits;

use Wordless\Application\Helpers\Arr;
use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Infrastructure\Wordpress\Listener;

trait ListenersBoot
{
    /**
     * @return void
     * @throws PathNotFoundException
     */
    public static function bootProviderListeners(): void
    {
        $config_prefix = self::LISTENERS_CONFIG_FILENAME . '.';
        $removable_hooks = Config::tryToGetOrDefault($config_prefix . self::LISTENERS_REMOVE_CONFIG_KEY, []);

        self::resolveListeners(
            Config::tryToGetOrDefault($config_prefix . self::LISTENERS_BOOT_CONFIG_KEY, []),
            $removable_hooks
        );

        self::resolveRemovableListeners($removable_hooks);
    }

    private static function resolveListeners(array $listeners, array &$removable_listeners): void
    {
        foreach ($listeners as $listener_class_namespace) {
            if ($removable_listeners[self::LISTENERS_REMOVE_ACTION_CONFIG_KEY][$listener_class_namespace] ?? false) {
                unset($removable_listeners[self::LISTENERS_REMOVE_ACTION_CONFIG_KEY][$listener_class_namespace]);
                continue;
            }

            if ($removable_listeners[self::LISTENERS_REMOVE_FILTER_CONFIG_KEY][$listener_class_namespace] ?? false) {
                unset($removable_listeners[self::LISTENERS_REMOVE_FILTER_CONFIG_KEY][$listener_class_namespace]);
                continue;
            }

            /** @var Listener $listener_class_namespace */
            $listener_class_namespace::hookIt();
        }
    }

    private static function resolveRemovableListeners(array $removable_listeners): void
    {
        foreach ($removable_listeners as $hook_type => $removable_listener) {
            $remove_single_hook_function = "remove_$hook_type";
            $remove_all_hook_function = "remove_all_{$hook_type}s";

            foreach ($removable_listener as $hook_flag => $remove_rules) {
                if (is_a($hook_flag, Listener::class, true)) {
                    continue;
                }

                if (!is_array($remove_rules)) {
                    $remove_all_hook_function($remove_rules);
                    continue;
                }

                if (Arr::isAssociative($remove_rules)) {
                    $remove_rules = [$remove_rules];
                }

                foreach ($remove_rules as $hook_remove_rules) {
                    $remove_single_hook_function(
                        $hook_flag,
                        $hook_remove_rules[self::LISTENERS_REMOVE_TYPE_FUNCTION_CONFIG_KEY],
                        $hook_remove_rules[self::LISTENERS_REMOVE_TYPE_PRIORITY_CONFIG_KEY] ?? 10
                    );
                }
            }
        }
    }
}
