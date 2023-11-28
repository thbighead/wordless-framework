<?php

namespace Wordless\Core;

use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Libraries\DesignPattern\Singleton;
use Wordless\Core\Bootstrapper\Exceptions\InvalidProviderClass;
use Wordless\Infrastructure\Provider;
use Wordless\Infrastructure\Provider\DTO\RemoveHookDTO;
use Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register\Exceptions\CustomPostTypeRegistrationFailed;

class Bootstrapper
{
    use Singleton;

    private array $prepared_listeners = [];
    private array $prepared_menus = [];
    /** @var Provider[] $providers */
    private array $providers = [];

    /**
     * @return void
     * @throws CustomPostTypeRegistrationFailed
     * @throws InvalidProviderClass
     * @throws PathNotFoundException
     */
    public static function boot(): void
    {
        self::getInstance()->loadProviders()
            ->loadListeners()
            ->loadMenus()
            ->bootIntoWordpress();
    }

    /**
     * @return void
     * @throws CustomPostTypeRegistrationFailed
     */
    private function bootIntoWordpress(): void
    {
        foreach ($this->providers as $provider) {
            $this->bootProviderServices($provider);
        }
    }

    /**
     * @param Provider $provider
     * @return void
     * @throws CustomPostTypeRegistrationFailed
     */
    private function bootProviderServices(Provider $provider): void
    {
        foreach ($provider->registerTaxonomies() as $customTaxonomyClassNamespace) {
            $customTaxonomyClassNamespace::register();
        }

        foreach ($provider->registerPostTypes() as $customPostTypeClassNamespace) {
            $customPostTypeClassNamespace::register();
        }

        $this->resolveRemovableActions($provider->unregisterActionListeners());
        $this->resolveRemovableFilters($provider->unregisterFilterListeners());
    }

    private function loadListeners(): static
    {
        foreach ($this->providers as $provider) {
            foreach ($provider->registerListeners() as $listener_namespace) {
                if ($provider->unregisterActionListeners()[$listener_namespace]
                    || $provider->unregisterFilterListeners()[$listener_namespace]) {
                    continue;
                }

                $this->prepared_listeners[$listener_namespace] = true;
            }
        }

        return $this;
    }

    private function loadMenus(): static
    {
        foreach ($this->providers as $provider) {
            foreach ($provider->registerMenus() as $menu_namespace) {
                $this->prepared_listeners[$menu_namespace] = true;
            }
        }

        return $this;
    }

    /**
     * @param string $provider_class_namespace
     * @return Provider
     * @throws InvalidProviderClass
     */
    private function loadProvider(string $provider_class_namespace): Provider
    {
        /** @var Provider $provider_class_namespace */
        if (!(($provider = $provider_class_namespace::getInstance()) instanceof Provider)) {
            throw new InvalidProviderClass($provider_class_namespace);
        }

        return $provider;
    }

    /**
     * @return $this
     * @throws InvalidProviderClass
     * @throws PathNotFoundException
     */
    private function loadProviders(): static
    {
        foreach (Config::get('providers') as $provider_class_namespace) {
            $this->providers[] = $this->loadProvider($provider_class_namespace);
        }

        return $this;
    }

    /**
     * @param RemoveHookDTO[] $removable_actions
     * @return void
     */
    private function resolveRemovableActions(array $removable_actions): void
    {
        foreach ($removable_actions as $removableAction) {
            if ($removableAction->isOnListener()) {
                continue;
            }

            $functions = $removableAction->getFunctions();

            if (empty($functions)) {
                remove_all_actions($removableAction->hook);
                continue;
            }

            foreach ($removableAction->getFunctions() as $function_used_on_hook) {
                remove_action(
                    $removableAction->hook,
                    $function_used_on_hook[RemoveHookDTO::FUNCTION_KEY],
                    $function_used_on_hook[RemoveHookDTO::PRIORITY_KEY] ?? 10
                );
            }
        }
    }

    /**
     * @param RemoveHookDTO[] $removable_filters
     * @return void
     */
    private function resolveRemovableFilters(array $removable_filters): void
    {
        foreach ($removable_filters as $removableFilter) {
            if ($removableFilter->isOnListener()) {
                continue;
            }

            $functions = $removableFilter->getFunctions();

            if (empty($functions)) {
                remove_all_filters($removableFilter->hook);
                continue;
            }

            foreach ($removableFilter->getFunctions() as $function_used_on_hook) {
                remove_filter(
                    $removableFilter->hook,
                    $function_used_on_hook[RemoveHookDTO::FUNCTION_KEY],
                    $function_used_on_hook[RemoveHookDTO::PRIORITY_KEY] ?? 10
                );
            }
        }
    }

    /**
     * @param RemoveHookDTO[] $removable_hooks
     * @return void
     */
    private function resolveRemovableHooks(array $removable_hooks, ): void
    {
        foreach ($removable_hooks as $removableFilter) {
            if ($removableFilter->isOnListener()) {
                continue;
            }

            $functions = $removableFilter->getFunctions();

            if (empty($functions)) {
                remove_all_filters($removableFilter->hook);
                continue;
            }

            foreach ($removableFilter->getFunctions() as $function_used_on_hook) {
                remove_filter(
                    $removableFilter->hook,
                    $function_used_on_hook[RemoveHookDTO::FUNCTION_KEY],
                    $function_used_on_hook[RemoveHookDTO::PRIORITY_KEY] ?? 10
                );
            }
        }
    }
}
