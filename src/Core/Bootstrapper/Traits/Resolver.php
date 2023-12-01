<?php

namespace Wordless\Core\Bootstrapper\Traits;

use Wordless\Core\Bootstrapper;
use Wordless\Core\Bootstrapper\Exceptions\DuplicatedMenuId;
use Wordless\Core\Bootstrapper\Exceptions\InvalidMenuClass;
use Wordless\Infrastructure\Provider\DTO\RemoveHookDTO;
use Wordless\Infrastructure\Wordpress\Menu;
use Wordless\Wordpress\Hook\Enums\Type;

trait Resolver
{
    private function resolveListeners(): void
    {
        foreach ($this->prepared_listeners as $listener_namespace) {
            $listener_namespace::hookIt();
        }
    }

    /**
     * @return $this
     * @throws DuplicatedMenuId
     * @throws InvalidMenuClass
     */
    private function resolveMenus(): static
    {
        $registrable_nav_menus = [];

        foreach ($this->prepared_menus as $menuClass) {
            if (!is_a($menuClass, Menu::class, true)) {
                throw new InvalidMenuClass($menuClass);
            }

            if ($menuFound = ($registrable_nav_menus[$menuClass::id()] ?? false)) {
                throw new DuplicatedMenuId($menuClass, $menuClass::id(), $menuFound);
            }

            $registrable_nav_menus[$menuClass::id()] = esc_html__($menuClass::name());
        }

        register_nav_menus($registrable_nav_menus);

        return $this;
    }

    /**
     * @param RemoveHookDTO[] $removable_actions
     * @return Bootstrapper
     */
    private function resolveRemovableActions(array $removable_actions): static
    {
        $this->resolveRemovableHooks($removable_actions, Type::action);

        return $this;
    }

    /**
     * @param RemoveHookDTO[] $removable_filters
     * @return Bootstrapper
     */
    private function resolveRemovableFilters(array $removable_filters): static
    {
        $this->resolveRemovableHooks($removable_filters, Type::filter);

        return $this;
    }

    /**
     * @param RemoveHookDTO[] $removable_hooks
     * @param Type $type
     * @return void
     */
    private function resolveRemovableHooks(array $removable_hooks, Type $type): void
    {
        $remove_all_function = "remove_all_{$type->name}s";
        $remove_single_function = "remove_$type->name";

        foreach ($removable_hooks as $removableHook) {
            if ($removableHook->isOnListener()) {
                unset($this->prepared_listeners[$removableHook->hook]);
                continue;
            }

            $functions_usec_on_hook = $removableHook->getFunctionsUsedOnHook();

            if (empty($functions_usec_on_hook)) {
                $remove_all_function($removableHook->hook);
                continue;
            }

            foreach ($functions_usec_on_hook as $function_used_on_hook) {
                $remove_single_function(
                    $removableHook->hook,
                    $function_used_on_hook[RemoveHookDTO::FUNCTION_KEY],
                    $function_used_on_hook[RemoveHookDTO::PRIORITY_KEY] ?? 10
                );
            }
        }
    }
}
