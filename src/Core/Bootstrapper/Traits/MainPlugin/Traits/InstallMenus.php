<?php

namespace Wordless\Core\Bootstrapper\Traits\MainPlugin\Traits;

use Wordless\Core\Bootstrapper\Exceptions\DuplicatedMenuId;
use Wordless\Core\Bootstrapper\Exceptions\InvalidMenuClass;
use Wordless\Infrastructure\Provider;
use Wordless\Infrastructure\Wordpress\Menu;

trait InstallMenus
{
    private array $loaded_menus = [];

    /**
     * @return string[]|Menu[]
     */
    private function getLoadedMenus(): array
    {
        return array_keys($this->loaded_menus);
    }

    private function loadMenus(Provider $provider): static
    {
        foreach ($provider->registerMenus() as $menu_namespace) {
            $this->loaded_menus[$menu_namespace] = true;
        }

        return $this;
    }

    /**
     * @return $this
     * @throws DuplicatedMenuId
     * @throws InvalidMenuClass
     */
    private function resolveMenus(): static
    {
        $registrable_nav_menus = [];

        foreach ($this->getLoadedMenus() as $menu_namespace) {
            if (!is_a($menu_namespace, Menu::class, true)) {
                throw new InvalidMenuClass($menu_namespace);
            }

            if ($menuFound = ($registrable_nav_menus[$menu_namespace::id()] ?? false)) {
                throw new DuplicatedMenuId($menu_namespace, $menu_namespace::id(), $menuFound);
            }

            $registrable_nav_menus[$menu_namespace::id()] = esc_html__($menu_namespace::name());
        }

        register_nav_menus($registrable_nav_menus);

        return $this;
    }
}
