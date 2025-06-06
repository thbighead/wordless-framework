<?php declare(strict_types=1);

namespace Wordless\Core;

use Symfony\Component\Dotenv\Exception\FormatException;
use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\Environment;
use Wordless\Application\Helpers\Environment\Exceptions\DotEnvNotSetException;
use Wordless\Application\Helpers\Environment\Exceptions\FailedToLoadDotEnv;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Libraries\DesignPattern\Singleton;
use Wordless\Core\Bootstrapper\Exceptions\ConstantAlreadyDefined;
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

    /** @var array<string, Provider> $loaded_providers */
    private array $loaded_providers;

    /**
     * @return void
     * @throws ConstantAlreadyDefined
     * @throws DotEnvNotSetException
     * @throws EmptyConfigKey
     * @throws FormatException
     * @throws InvalidProviderClass
     * @throws PathNotFoundException
     */
    public static function bootConstants(): void
    {
        foreach (self::getInstance()->getLoadedProviders() as $provider) {
            foreach ($provider->registerConstants() as $constant_name => $constant_value) {
                if (defined($constant_name)) {
                    throw new ConstantAlreadyDefined($constant_name);
                }

                define($constant_name, $constant_value);
            }
        }
    }

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
     * @return Provider[]
     */
    public function getLoadedProviders(): array
    {
        return $this->loaded_providers ?? [];
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

        foreach ($provider->registerProviders() as $registered_provider_class_namespace) {
            if (!isset($this->loaded_providers[$registered_provider_class_namespace])) {
                $this->loaded_providers[$registered_provider_class_namespace] = $this->loadProvider($registered_provider_class_namespace);
            }
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
            if (!isset($this->loaded_providers[$provider_class_namespace])) {
                $this->loaded_providers[$provider_class_namespace] = $this->loadProvider($provider_class_namespace);
            }
        }

        return $this;
    }

    /**
     * @return self
     * @throws DotEnvNotSetException
     * @throws EmptyConfigKey
     * @throws FailedToLoadDotEnv
     * @throws PathNotFoundException
     */
    private function setErrorReporting(): self
    {
        error_reporting(Config::wordpressAdmin(
            self::CONFIG_KEY_ERROR_REPORTING,
            Environment::isNotLocal()
                ? E_ALL & ~E_WARNING & ~E_NOTICE & ~E_DEPRECATED & ~E_USER_DEPRECATED
                : E_ALL
        ));

        return $this;
    }
}
