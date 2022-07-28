<?php

namespace Wordless\Abstractions;

use Wordless\Exceptions\DuplicatedMenuId;
use Wordless\Exceptions\InvalidMenuClass;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\Arr;
use Wordless\Helpers\ProjectPath;

class Bootstrapper
{
    public const HOOKERS_BOOT_CONFIG_KEY = 'boot';
    public const HOOKERS_REMOVE_ACTION_CONFIG_KEY = 'action';
    public const HOOKERS_REMOVE_CONFIG_KEY = 'remove';
    public const HOOKERS_REMOVE_FILTER_CONFIG_KEY = 'filter';
    public const HOOKERS_REMOVE_TYPE_FUNCTION_CONFIG_KEY = 'function';
    public const HOOKERS_REMOVE_TYPE_PRIORITY_CONFIG_KEY = 'priority';
    public const MENUS_CONFIG_KEY = 'menus';

    /**
     * @return void
     * @throws PathNotFoundException
     */
    public static function bootHookers()
    {
        $hookers_config = include ProjectPath::config('hookers.php');
        $removable_hooks = $hookers_config[self::HOOKERS_REMOVE_CONFIG_KEY] ?? [];

        self::resolveHookers($hookers_config[self::HOOKERS_BOOT_CONFIG_KEY] ?? [], $removable_hooks);

        self::resolveRemovableHooks($removable_hooks);
    }

    /**
     * @return void
     * @throws DuplicatedMenuId
     * @throws InvalidMenuClass
     * @throws PathNotFoundException
     */
    public static function bootMenus()
    {
        $menus_config = (include ProjectPath::config('admin.php'))[self::MENUS_CONFIG_KEY];

        self::resolveMenus($menus_config);
    }

    private static function resolveHookers(array $hookers, array &$removable_hooks)
    {
        foreach ($hookers as $hooker_class_namespace) {
            if ($removable_hooks[self::HOOKERS_REMOVE_ACTION_CONFIG_KEY][$hooker_class_namespace] ?? false) {
                unset($removable_hooks[self::HOOKERS_REMOVE_ACTION_CONFIG_KEY][$hooker_class_namespace]);
                continue;
            }

            if ($removable_hooks[self::HOOKERS_REMOVE_FILTER_CONFIG_KEY][$hooker_class_namespace] ?? false) {
                unset($removable_hooks[self::HOOKERS_REMOVE_FILTER_CONFIG_KEY][$hooker_class_namespace]);
                continue;
            }

            /** @var AbstractHooker $hooker_class_namespace */
            $hooker_class_namespace::hookIt();
        }
    }

    /**
     * @param array $menus_config
     * @return void
     * @throws DuplicatedMenuId
     * @throws InvalidMenuClass
     */
    private static function resolveMenus(array $menus_config)
    {
        $registerable_nav_menus = [];

        foreach ($menus_config as $menuClass) {
            if ($menuFound = ($registerable_nav_menus[$menuClass::id()] ?? false)) {
                throw new DuplicatedMenuId($menuClass, $menuClass::id(), $menuFound);
            }

            if (!is_a($menuClass, AbstractMenu::class, true)) {
                throw new InvalidMenuClass($menuClass);
            }

            $registerable_nav_menus[$menuClass::id()] = esc_html__($menuClass::name());
        }

        register_nav_menus($registerable_nav_menus);
    }

    private static function resolveRemovableHooks(array $removable_hooks)
    {
        foreach ($removable_hooks as $hook_type => $removable_hook) {
            $remove_single_hook_function = "remove_$hook_type";
            $remove_all_hook_function = "remove_all_{$hook_type}s";

            foreach ($removable_hook as $hook_flag => $remove_rules) {
                if (is_a($hook_flag, AbstractHooker::class, true)) {
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
                        $hook_remove_rules[self::HOOKERS_REMOVE_TYPE_FUNCTION_CONFIG_KEY],
                        $hook_remove_rules[self::HOOKERS_REMOVE_TYPE_PRIORITY_CONFIG_KEY] ?? 10
                    );
                }
            }
        }
    }
}