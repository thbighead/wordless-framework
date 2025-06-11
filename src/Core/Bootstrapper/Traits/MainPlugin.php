<?php declare(strict_types=1);

namespace Wordless\Core\Bootstrapper\Traits;

use InvalidArgumentException;
use Symfony\Component\Dotenv\Exception\FormatException;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\Environment\Exceptions\DotEnvNotSetException;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Core\Bootstrapper\Exceptions\DuplicatedMenuId;
use Wordless\Core\Bootstrapper\Exceptions\FailedToLoadErrorReportingConfiguration;
use Wordless\Core\Bootstrapper\Exceptions\InvalidMenuClass;
use Wordless\Core\Bootstrapper\Exceptions\InvalidProviderClass;
use Wordless\Core\Bootstrapper\Traits\MainPlugin\Exceptions\FailedToBootEnqueueables;
use Wordless\Core\Bootstrapper\Traits\MainPlugin\Exceptions\FailedToBootMainPlugin;
use Wordless\Core\Bootstrapper\Traits\MainPlugin\Exceptions\FailedToResolveMenu;
use Wordless\Core\Bootstrapper\Traits\MainPlugin\Traits\InstallEnqueueables;
use Wordless\Core\Bootstrapper\Traits\MainPlugin\Traits\InstallListeners;
use Wordless\Core\Bootstrapper\Traits\MainPlugin\Traits\InstallMenus;
use Wordless\Infrastructure\Provider;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset\Exceptions\DuplicatedEnqueueableId;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset\Exceptions\InvalidEnqueueableId;

trait MainPlugin
{
    use InstallEnqueueables;
    use InstallListeners;
    use InstallMenus;

    /**
     * @return void
     * @throws FailedToBootMainPlugin
     */
    public static function bootMainPlugin(): void
    {
        try {
            self::getInstance()->bootIntoWordpress();
        } catch (FailedToLoadErrorReportingConfiguration|FailedToResolveMenu $exception) {
            throw new FailedToBootMainPlugin($exception);
        }
    }

    /**
     * @param bool $on_admin
     * @return void
     * @throws FailedToBootEnqueueables
     */
    public static function bootEnqueues(bool $on_admin = false): void
    {
        try {
            self::getInstance()->resolveEnqueues($on_admin);
        } catch (FailedToLoadErrorReportingConfiguration|InvalidEnqueueableId $exception) {
            throw new FailedToBootEnqueueables($on_admin, $exception);
        }
    }

    /**
     * @return void
     * @throws FailedToResolveMenu
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
     * @throws FailedToResolveMenu
     */
    private function finishWordpressServicesBoot(): void
    {
        try {
            $this->resolveListeners()
                ->resolveMenus();
        } catch (DuplicatedMenuId|InvalidMenuClass $exception) {
            throw new FailedToResolveMenu($exception);
        }
    }
}
