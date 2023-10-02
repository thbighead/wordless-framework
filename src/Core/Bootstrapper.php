<?php

namespace Wordless\Core;

use Wordless\Application\Helpers\Arr;
use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\Config\Exceptions\InvalidConfigKey;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Core\Bootstrapper\Exceptions\DuplicatedMenuId;
use Wordless\Core\Bootstrapper\Exceptions\InvalidMenuClass;
use Wordless\Infrastructure\Wordpress\Listener;
use Wordless\Infrastructure\Wordpress\Menu;

class Bootstrapper
{
    final public const ERROR_REPORTING_KEY = 'error_reporting';
    final public const LISTENERS_BOOT_CONFIG_KEY = 'boot';
    final public const LISTENERS_REMOVE_ACTION_CONFIG_KEY = 'action';
    final public const LISTENERS_REMOVE_CONFIG_KEY = 'remove';
    final public const LISTENERS_REMOVE_FILTER_CONFIG_KEY = 'filter';
    final public const LISTENERS_REMOVE_TYPE_FUNCTION_CONFIG_KEY = 'function';
    final public const LISTENERS_REMOVE_TYPE_PRIORITY_CONFIG_KEY = 'priority';
    final public const MENUS_CONFIG_KEY = 'menus';
    private const ADMIN_CONFIG_FILENAME = 'admin';
    private const LISTENERS_CONFIG_FILENAME = 'listeners';

    /**
     * @return void
     * @throws PathNotFoundException
     */
    public static function bootListeners(): void
    {
        $config_prefix = self::LISTENERS_CONFIG_FILENAME . '.';
        $removable_hooks = Config::tryToGetOrDefault($config_prefix . self::LISTENERS_REMOVE_CONFIG_KEY, []);

        self::resolveListeners(
            Config::tryToGetOrDefault($config_prefix . self::LISTENERS_BOOT_CONFIG_KEY, []),
            $removable_hooks
        );

        self::resolveRemovableHooks($removable_hooks);
    }

    /**
     * @return void
     * @throws DuplicatedMenuId
     * @throws InvalidMenuClass
     * @throws PathNotFoundException
     * @throws InvalidConfigKey
     */
    public static function bootMenus(): void
    {
        self::resolveMenus(Config::get(self::ADMIN_CONFIG_FILENAME . '.' . self::MENUS_CONFIG_KEY));
    }

    private static function resolveListeners(array $hookers, array &$removable_hooks): void
    {
        foreach ($hookers as $hooker_class_namespace) {
            if ($removable_hooks[self::LISTENERS_REMOVE_ACTION_CONFIG_KEY][$hooker_class_namespace] ?? false) {
                unset($removable_hooks[self::LISTENERS_REMOVE_ACTION_CONFIG_KEY][$hooker_class_namespace]);
                continue;
            }

            if ($removable_hooks[self::LISTENERS_REMOVE_FILTER_CONFIG_KEY][$hooker_class_namespace] ?? false) {
                unset($removable_hooks[self::LISTENERS_REMOVE_FILTER_CONFIG_KEY][$hooker_class_namespace]);
                continue;
            }

            /** @var Listener $hooker_class_namespace */
            $hooker_class_namespace::hookIt();
        }
    }

    /**
     * @param array $menus_config
     * @return void
     * @throws DuplicatedMenuId
     * @throws InvalidMenuClass
     */
    private static function resolveMenus(array $menus_config): void
    {
        $registrable_nav_menus = [];

        foreach ($menus_config as $menuClass) {
            if (!is_a($menuClass, Menu::class, true)) {
                throw new InvalidMenuClass($menuClass);
            }

            if ($menuFound = ($registrable_nav_menus[$menuClass::id()] ?? false)) {
                throw new DuplicatedMenuId($menuClass, $menuClass::id(), $menuFound);
            }

            $registrable_nav_menus[$menuClass::id()] = esc_html__($menuClass::name());
        }

        register_nav_menus($registrable_nav_menus);
    }

    private static function resolveRemovableHooks(array $removable_hooks): void
    {
        foreach ($removable_hooks as $hook_type => $removable_hook) {
            $remove_single_hook_function = "remove_$hook_type";
            $remove_all_hook_function = "remove_all_{$hook_type}s";

            foreach ($removable_hook as $hook_flag => $remove_rules) {
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
