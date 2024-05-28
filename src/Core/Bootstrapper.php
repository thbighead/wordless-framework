<?php declare(strict_types=1);

namespace Wordless\Core;

use Symfony\Component\Dotenv\Exception\FormatException;
use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\Environment;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Libraries\DesignPattern\Singleton;
use Wordless\Core\Bootstrapper\Exceptions\InvalidProviderClass;
use Wordless\Core\Bootstrapper\Traits\ApiControllers;
use Wordless\Core\Bootstrapper\Traits\Console;
use Wordless\Core\Bootstrapper\Traits\Entities;
use Wordless\Core\Bootstrapper\Traits\InternalCachers;
use Wordless\Core\Bootstrapper\Traits\MainPlugin;
use Wordless\Core\Bootstrapper\Traits\Migrations;
use Wordless\Core\Bootstrapper\Traits\PublishConfigs;
use Wordless\Core\Bootstrapper\Traits\Schedules;
use Wordless\Infrastructure\Provider;
use Wordless\Core\Exceptions\DotEnvNotSetException;

final class Bootstrapper extends Singleton
{
    use ApiControllers;
    use Console;
    use Entities;
    use InternalCachers;
    use MainPlugin;
    use Migrations;
    use PublishConfigs;
    use Schedules;

    final public const CONFIG_KEY_ERROR_REPORTING = 'error_reporting';

    /** @var Provider[] $loaded_providers */
    private array $loaded_providers;

    /**
     * @return static
     * @throws DotEnvNotSetException
     * @throws EmptyConfigKey
     * @throws FormatException
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
     * @throws DotEnvNotSetException
     * @throws EmptyConfigKey
     * @throws FormatException
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
     * @throws InvalidProviderClass
     * @throws PathNotFoundException
     */
    private function loadProviders(): Bootstrapper
    {
        if (!empty($this->loaded_providers)) {
            return $this;
        }

        foreach (Config::wordless(Provider::CONFIG_KEY) as $provider_class_namespace) {
            $this->loaded_providers[] = $this->loadProvider($provider_class_namespace);
        }

        return $this;
    }

    /**
     * @return self
     * @throws EmptyConfigKey
     * @throws DotEnvNotSetException
     * @throws PathNotFoundException
     * @throws FormatException
     */
    private function setErrorReporting(): self
    {
        error_reporting(Config::wordpressAdmin(
            self::CONFIG_KEY_ERROR_REPORTING,
            Environment::isProduction() ? E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED : E_ALL
        ));

        return $this;
    }
}
