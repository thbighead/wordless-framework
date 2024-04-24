<?php declare(strict_types=1);

namespace Wordless\Core\Bootstrapper\Traits;

use InvalidArgumentException;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\Config\Exceptions\InvalidConfigKey;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Core\Bootstrapper\Exceptions\DuplicatedMenuId;
use Wordless\Core\Bootstrapper\Exceptions\InvalidMenuClass;
use Wordless\Core\Bootstrapper\Exceptions\InvalidProviderClass;
use Wordless\Core\Bootstrapper\Traits\MainPlugin\Traits\InstallCustomPostTypes;
use Wordless\Core\Bootstrapper\Traits\MainPlugin\Traits\InstallCustomTaxonomies;
use Wordless\Core\Bootstrapper\Traits\MainPlugin\Traits\InstallEnqueueables;
use Wordless\Core\Bootstrapper\Traits\MainPlugin\Traits\InstallListeners;
use Wordless\Core\Bootstrapper\Traits\MainPlugin\Traits\InstallMenus;
use Wordless\Infrastructure\Provider;
use Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register\Exceptions\CustomPostTypeRegistrationFailed;
use Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register\Traits\Validation\Exceptions\InvalidCustomPostTypeKey;
use Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register\Traits\Validation\Exceptions\ReservedCustomPostTypeKey;
use Wordless\Infrastructure\Wordpress\CustomPostStatus\Traits\Register\Traits\Validation\Exceptions\ReservedCustomPostStatusKey;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset\Exceptions\DuplicatedEnqueueableId;
use Wordless\Infrastructure\Wordpress\Taxonomy\CustomTaxonomy\Traits\Register\Exceptions\CustomTaxonomyRegistrationFailed;
use Wordless\Infrastructure\Wordpress\Taxonomy\CustomTaxonomy\Traits\Register\Validation\Exceptions\InvalidCustomTaxonomyName;
use Wordless\Infrastructure\Wordpress\Taxonomy\CustomTaxonomy\Traits\Register\Validation\Exceptions\ReservedCustomTaxonomyName;
use Wordless\Wordpress\Hook\Enums\Action;

trait MainPlugin
{
    use InstallCustomPostTypes;
    use InstallCustomTaxonomies;
    use InstallEnqueueables;
    use InstallListeners;
    use InstallMenus;

    /**
     * @return void
     * @throws CustomPostTypeRegistrationFailed
     * @throws CustomTaxonomyRegistrationFailed
     * @throws DuplicatedMenuId
     * @throws EmptyConfigKey
     * @throws InvalidArgumentException
     * @throws InvalidCustomPostTypeKey
     * @throws InvalidCustomTaxonomyName
     * @throws InvalidMenuClass
     * @throws InvalidProviderClass
     * @throws PathNotFoundException
     * @throws ReservedCustomPostStatusKey
     * @throws ReservedCustomPostTypeKey
     * @throws ReservedCustomTaxonomyName
     */
    public static function bootMainPlugin(): void
    {
        self::getInstance()->bootIntoWordpress();
    }

    /**
     * @return void
     * @throws DuplicatedEnqueueableId
     * @throws EmptyConfigKey
     * @throws InvalidArgumentException
     * @throws InvalidProviderClass
     * @throws PathNotFoundException
     */
    public static function bootEnqueues(): void
    {
        self::getInstance()->resolveEnqueues();
    }

    /**
     * @return void
     * @throws CustomPostTypeRegistrationFailed
     * @throws CustomTaxonomyRegistrationFailed
     * @throws DuplicatedMenuId
     * @throws InvalidArgumentException
     * @throws InvalidCustomPostTypeKey
     * @throws InvalidCustomTaxonomyName
     * @throws InvalidMenuClass
     * @throws ReservedCustomPostStatusKey
     * @throws ReservedCustomPostTypeKey
     * @throws ReservedCustomTaxonomyName
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
     * @throws InvalidArgumentException
     * @throws InvalidCustomPostTypeKey
     * @throws InvalidCustomTaxonomyName
     * @throws ReservedCustomPostStatusKey
     * @throws ReservedCustomPostTypeKey
     * @throws ReservedCustomTaxonomyName
     * @throws CustomTaxonomyRegistrationFailed
     */
    private function preBootWordpressServicesFromProvider(Provider $provider): void
    {
        add_action(
            Action::init->value,
            function () use ($provider) {
                foreach ($provider->registerTaxonomies() as $customTaxonomyClassNamespace) {
                    $customTaxonomyClassNamespace::register();
                }

                foreach ($provider->registerPostStatus() as $customPostStatusClassNamespace) {
                    $customPostStatusClassNamespace::register();
                }

                foreach ($provider->registerPostTypes() as $customPostTypeClassNamespace) {
                    $customPostTypeClassNamespace::register();
                }
            },
            accepted_args: 0
        );

        foreach ($provider->registerSchedules() as $scheduleClassNamespace) {
            $scheduleClassNamespace::registerHook();
        }

        $this->loadMenus($provider)
            ->loadListeners($provider)
            ->loadEnqueueableAssets($provider)
            ->resolveRemovableActions($provider->unregisterActionListeners())
            ->resolveRemovableFilters($provider->unregisterFilterListeners())
            ->loadCustomTaxonomies($provider)
            ->loadCustomPostTypes($provider);
    }

    /**
     * @return void
     * @throws CustomPostTypeRegistrationFailed
     * @throws DuplicatedMenuId
     * @throws InvalidArgumentException
     * @throws InvalidCustomPostTypeKey
     * @throws InvalidCustomTaxonomyName
     * @throws InvalidMenuClass
     * @throws ReservedCustomPostTypeKey
     * @throws ReservedCustomTaxonomyName
     */
    private function finishWordpressServicesBoot(): void
    {
        $this->resolveListeners()
            ->resolveMenus()
            ->resolveCustomTaxonomies()
            ->resolveCustomPostTypes();
    }
}
