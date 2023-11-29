<?php

namespace Wordless\Core;

use Exception;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Exception\LogicException;
use Wordless\Application\Helpers\Config\Exceptions\InvalidConfigKey;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Libraries\DesignPattern\Singleton;
use Wordless\Core\Bootstrapper\Exceptions\DuplicatedMenuId;
use Wordless\Core\Bootstrapper\Exceptions\InvalidMenuClass;
use Wordless\Core\Bootstrapper\Exceptions\InvalidProviderClass;
use Wordless\Core\Bootstrapper\Traits\Loader;
use Wordless\Core\Bootstrapper\Traits\Resolver;
use Wordless\Infrastructure\Provider;
use Wordless\Infrastructure\Wordpress\ApiController;
use Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register\Exceptions\CustomPostTypeRegistrationFailed;
use Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register\Traits\Validation\Exceptions\InvalidCustomPostTypeKey;
use Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register\Traits\Validation\Exceptions\ReservedCustomPostTypeKey;
use Wordless\Infrastructure\Wordpress\Listener;
use Wordless\Infrastructure\Wordpress\Menu;
use Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Register\Validation\Exceptions\InvalidCustomTaxonomyName;

final class Bootstrapper extends Singleton
{
    use Loader;
    use Resolver;

    public const ERROR_REPORTING_KEY = 'error_reporting';
    /** @var string[]|Listener[] $prepared_listeners */
    private array $prepared_listeners = [];
    /** @var string[]|Menu[] $prepared_menus */
    private array $prepared_menus = [];
    /** @var Provider[] $providers */
    private array $providers = [];

    /**
     * @param Application $application
     * @return void
     * @throws InvalidConfigKey
     * @throws InvalidProviderClass
     * @throws LogicException
     * @throws PathNotFoundException
     */
    public static function bootConsole(Application $application): void
    {
        self::getInstance()->bootIntoSymfonyApplication($application);
    }

    /**
     * @return void
     * @throws CustomPostTypeRegistrationFailed
     * @throws DuplicatedMenuId
     * @throws InvalidConfigKey
     * @throws InvalidCustomPostTypeKey
     * @throws InvalidCustomTaxonomyName
     * @throws InvalidMenuClass
     * @throws InvalidProviderClass
     * @throws PathNotFoundException
     * @throws ReservedCustomPostTypeKey
     */
    public static function bootMainPlugin(): void
    {
        self::getInstance()->bootIntoWordpress();
    }

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
     * @return string[]|ApiController[]
     */
    public function getProvidedApiControllers(): array
    {
        $api_controllers_namespaces = [];

        foreach ($this->providers as $provider) {
            foreach ($provider->registerApiControllers() as $api_controller_namespace) {
                $api_controllers_namespaces[$api_controller_namespace] = $api_controller_namespace;
            }
        }

        return array_values($api_controllers_namespaces);
    }

    /**
     * @param Application $application
     * @return void
     * @throws LogicException
     */
    private function bootIntoSymfonyApplication(Application $application): void
    {
        foreach ($this->providers as $provider) {
            foreach ($provider->registerCommands() as $command_namespace) {
                $application->add(new $command_namespace);
            }
        }

        try {
            $application->run();
        } catch (Exception $exception) {
            echo Str::finishWith($exception->getMessage(), "\n");
        }
    }

    /**
     * @return void
     * @throws CustomPostTypeRegistrationFailed
     * @throws DuplicatedMenuId
     * @throws InvalidCustomPostTypeKey
     * @throws InvalidCustomTaxonomyName
     * @throws InvalidMenuClass
     * @throws ReservedCustomPostTypeKey
     */
    private function bootIntoWordpress(): void
    {
        foreach ($this->providers as $provider) {
            $this->bootProviderWordpressServices($provider);
        }
    }

    /**
     * @param Provider $provider
     * @return void
     * @throws CustomPostTypeRegistrationFailed
     * @throws DuplicatedMenuId
     * @throws InvalidMenuClass
     * @throws InvalidCustomPostTypeKey
     * @throws ReservedCustomPostTypeKey
     * @throws InvalidCustomTaxonomyName
     */
    private function bootProviderWordpressServices(Provider $provider): void
    {
        foreach ($provider->registerTaxonomies() as $customTaxonomyClassNamespace) {
            $customTaxonomyClassNamespace::register();
        }

        foreach ($provider->registerPostTypes() as $customPostTypeClassNamespace) {
            $customPostTypeClassNamespace::register();
        }

        $this->resolveMenus()
            ->resolveRemovableActions($provider->unregisterActionListeners())
            ->resolveRemovableFilters($provider->unregisterFilterListeners())
            ->resolveListeners();
    }
}
