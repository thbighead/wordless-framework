<?php declare(strict_types=1);

namespace Wordless\Core\Bootstrapper\Traits;

use InvalidArgumentException;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Core\Bootstrapper\Exceptions\DuplicatedMenuId;
use Wordless\Core\Bootstrapper\Exceptions\InvalidMenuClass;
use Wordless\Core\Bootstrapper\Exceptions\InvalidProviderClass;
use Wordless\Core\Bootstrapper\Traits\MainPlugin\Traits\InstallEnqueueables;
use Wordless\Core\Bootstrapper\Traits\MainPlugin\Traits\InstallListeners;
use Wordless\Core\Bootstrapper\Traits\MainPlugin\Traits\InstallMenus;
use Wordless\Infrastructure\Provider;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset\Exceptions\DuplicatedEnqueueableId;

trait MainPlugin
{
    use InstallEnqueueables;
    use InstallListeners;
    use InstallMenus;

    /**
     * @return void
     * @throws DuplicatedMenuId
     * @throws EmptyConfigKey
     * @throws InvalidMenuClass
     * @throws InvalidProviderClass
     * @throws PathNotFoundException
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
     * @throws DuplicatedMenuId
     * @throws InvalidMenuClass
     */
    private function bootIntoWordpress(): void
    {
        foreach ($this->loaded_providers as $provider) {
            $this->preBootWordpressServicesFromProvider($provider);
        }

        $this->finishWordpressServicesBoot();
    }

    private function preBootWordpressServicesFromProvider(Provider $provider): void
    {
        foreach ($provider->registerSchedules() as $scheduleClassNamespace) {
            $scheduleClassNamespace::registerHook();
        }

        $this->loadMenus($provider)
            ->loadListeners($provider)
            ->loadEnqueueableAssets($provider)
            ->resolveRemovableActions($provider->unregisterActionListeners())
            ->resolveRemovableFilters($provider->unregisterFilterListeners())
            ->loadCustomTaxonomies($provider)
            ->loadCustomPostStatuses($provider)
            ->loadCustomPostTypes($provider);
    }

    /**
     * @return void
     * @throws DuplicatedMenuId
     * @throws InvalidMenuClass
     */
    private function finishWordpressServicesBoot(): void
    {
        $this->resolveListeners()
            ->resolveMenus();
    }
}
