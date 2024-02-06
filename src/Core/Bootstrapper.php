<?php declare(strict_types=1);

namespace Wordless\Core;

use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
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
use Wordless\Core\Bootstrapper\Traits\Schedules;
use Wordless\Infrastructure\Provider;

final class Bootstrapper extends Singleton
{
    use ApiControllers;
    use Console;
    use InternalCachers;
    use MainPlugin;
    use Migrations;
    use Schedules;

    public const ERROR_REPORTING_KEY = 'error_reporting';

    /** @var Provider[] $loaded_providers */
    private array $loaded_providers;

    /**
     * @return static
     * @throws EmptyConfigKey
     * @throws InvalidConfigKey
     * @throws InvalidProviderClass
     * @throws PathNotFoundException
     * @noinspection PhpUnnecessaryStaticReferenceInspection
     */
    public static function getInstance(): static
    {
        return parent::getInstance()->load();
    }

    /**
     * @return Bootstrapper
     * @throws EmptyConfigKey
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

        foreach (Config::getOrFail('wordless.providers') as $provider_class_namespace) {
            $this->loaded_providers[] = $this->loadProvider($provider_class_namespace);
        }

        return $this;
    }

    /**
     * @return self
     * @throws EmptyConfigKey
     * @throws PathNotFoundException
     */
    private function setErrorReporting(): self
    {
        error_reporting(Config::wordpress()->ofKey('admin')->get(
            self::ERROR_REPORTING_KEY,
            Environment::isProduction() ? E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED : E_ALL
        ));

        return $this;
    }
}
