<?php declare(strict_types=1);

namespace Wordless\Core;

use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\Config\Exceptions\InvalidConfigKey;
use Wordless\Application\Helpers\Environment;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Libraries\DesignPattern\Singleton;
use Wordless\Core\Bootstrapper\Exceptions\InvalidProviderClass;
use Wordless\Core\Bootstrapper\Traits\ApiControllers;
use Wordless\Core\Bootstrapper\Traits\Console;
use Wordless\Core\Bootstrapper\Traits\InternalCachers;
use Wordless\Core\Bootstrapper\Traits\MainPlugin;
use Wordless\Core\Bootstrapper\Traits\Migrations;
use Wordless\Infrastructure\Provider;

final class Bootstrapper extends Singleton
{
    use ApiControllers;
    use Console;
    use InternalCachers;
    use MainPlugin;
    use Migrations;

    public const ERROR_REPORTING_KEY = 'error_reporting';

    /** @var Provider[] $loaded_providers */
    private array $loaded_providers;

    /**
     * @return Bootstrapper
     * @throws InvalidConfigKey
     * @throws InvalidProviderClass
     * @throws PathNotFoundException
     */
    public static function getInstance(): self
    {
        return parent::getInstance()->load();
    }

    /**
     * @return Bootstrapper
     * @throws InvalidConfigKey
     * @throws InvalidProviderClass
     * @throws PathNotFoundException
     */
    private function load(): Bootstrapper
    {
        return $this->setErrorReporting()
            ->loadProviders();
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

        foreach ($provider->registerProviders() as $provider_class_namespace) {
            $this->loaded_providers[] = $this->loadProvider($provider_class_namespace);
        }

        return $provider;
    }

    /**
     * @return Bootstrapper
     * @throws InvalidConfigKey
     * @throws InvalidProviderClass
     * @throws PathNotFoundException
     */
    private function loadProviders(): Bootstrapper
    {
        if (!empty($this->loaded_providers)) {
            return $this;
        }

        foreach (Config::get('wordless.providers') as $provider_class_namespace) {
            $this->loaded_providers[] = $this->loadProvider($provider_class_namespace);
        }

        return $this;
    }

    /**
     * @return $this
     * @throws PathNotFoundException
     */
    private function setErrorReporting(): self
    {
        error_reporting(Config::tryToGetOrDefault(
            'wordpress.admin.' . self::ERROR_REPORTING_KEY,
            Environment::isProduction()
                ? E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED
                : E_ALL
        ));

        return $this;
    }
}
