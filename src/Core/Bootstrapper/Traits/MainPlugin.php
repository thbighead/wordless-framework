<?php

namespace Wordless\Core\Bootstrapper\Traits;

use InvalidArgumentException;
use Wordless\Application\Helpers\Config\Exceptions\InvalidConfigKey;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Core\Bootstrapper\Exceptions\DuplicatedMenuId;
use Wordless\Core\Bootstrapper\Exceptions\InvalidMenuClass;
use Wordless\Core\Bootstrapper\Exceptions\InvalidProviderClass;
use Wordless\Core\Bootstrapper\Traits\MainPlugin\Traits\InstallListeners;
use Wordless\Core\Bootstrapper\Traits\MainPlugin\Traits\InstallMenus;
use Wordless\Infrastructure\Provider;
use Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register\Exceptions\CustomPostTypeRegistrationFailed;
use Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register\Traits\Validation\Exceptions\InvalidCustomPostTypeKey;
use Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register\Traits\Validation\Exceptions\ReservedCustomPostTypeKey;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset\Exceptions\DuplicatedEnqueueableId;
use Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Register\Validation\Exceptions\InvalidCustomTaxonomyName;
use Wordless\Core\Bootstrapper\Traits\MainPlugin\Traits\InstallEnqueueables;

trait MainPlugin
{
    use InstallEnqueueables;
    use InstallListeners;
    use InstallMenus;

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
        foreach ($this->loaded_providers as $provider) {
            $this->preBootWordpressServicesFromProvider($provider);
        }

        $this->finishWordpressServicesBoot();
    }

    /**
     * @param Provider $provider
     * @return void
     * @throws CustomPostTypeRegistrationFailed
     * @throws InvalidCustomPostTypeKey
     * @throws InvalidCustomTaxonomyName
     * @throws ReservedCustomPostTypeKey
     */
    private function preBootWordpressServicesFromProvider(Provider $provider): void
    {
        foreach ($provider->registerTaxonomies() as $customTaxonomyClassNamespace) {
            $customTaxonomyClassNamespace::register();
        }

        foreach ($provider->registerPostTypes() as $customPostTypeClassNamespace) {
            $customPostTypeClassNamespace::register();
        }

        $this->loadMenus($provider)
            ->loadListeners($provider)
            ->loadEnqueueableAssets($provider)
            ->resolveRemovableActions($provider->unregisterActionListeners())
            ->resolveRemovableFilters($provider->unregisterFilterListeners());
    }

    /**
     * @return void
     * @throws DuplicatedMenuId
     * @throws InvalidMenuClass
     * @throws PathNotFoundException
     * @throws InvalidArgumentException
     * @throws DuplicatedEnqueueableId
     */
    private function finishWordpressServicesBoot(): void
    {
        $this->resolveListeners()
            ->resolveMenus()
            ->resolveEnqueues();
    }
}
