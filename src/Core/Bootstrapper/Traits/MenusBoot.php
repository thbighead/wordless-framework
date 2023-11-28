<?php

namespace Wordless\Core\Bootstrapper\Traits;

use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\Config\Exceptions\InvalidConfigKey;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Core\Bootstrapper\Exceptions\DuplicatedMenuId;
use Wordless\Core\Bootstrapper\Exceptions\InvalidMenuClass;
use Wordless\Infrastructure\Wordpress\Menu;

trait MenusBoot
{
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
}
