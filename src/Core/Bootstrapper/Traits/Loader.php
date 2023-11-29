<?php

namespace Wordless\Core\Bootstrapper\Traits;

use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\Config\Exceptions\InvalidConfigKey;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Core\Bootstrapper\Exceptions\InvalidProviderClass;
use Wordless\Infrastructure\Provider;

trait Loader
{
    /**
     * @return $this
     * @throws InvalidConfigKey
     * @throws InvalidProviderClass
     * @throws PathNotFoundException
     */
    private function load(): static
    {
        return $this->setErrorReporting()
            ->loadProviders()
            ->loadListeners()
            ->loadMenus();
    }

    private function loadListeners(): static
    {
        if (!empty($this->prepared_listeners)) {
            return $this;
        }

        foreach ($this->providers as $provider) {
            foreach ($provider->registerListeners() as $listener_namespace) {
                $this->prepared_listeners[$listener_namespace] = true;
            }
        }

        return $this;
    }

    private function loadMenus(): static
    {
        if (!empty($this->prepared_menus)) {
            return $this;
        }

        foreach ($this->providers as $provider) {
            foreach ($provider->registerMenus() as $menu_namespace) {
                $this->prepared_menus[$menu_namespace] = true;
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
     * @throws InvalidConfigKey
     * @throws InvalidProviderClass
     * @throws PathNotFoundException
     */
    private function loadProviders(): static
    {
        if (!empty($this->providers)) {
            return $this;
        }

        foreach (Config::get('providers') as $provider_class_namespace) {
            $this->providers[] = $this->loadProvider($provider_class_namespace);
        }

        return $this;
    }
}
