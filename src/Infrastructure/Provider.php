<?php declare(strict_types=1);

namespace Wordless\Infrastructure;

use Wordless\Application\Libraries\DesignPattern\Singleton\Traits\Constructors;
use Wordless\Infrastructure\Provider\Traits\EnqueueablesRegistration;
use Wordless\Infrastructure\Provider\Traits\ListenersRegistration;
use Wordless\Infrastructure\Wordpress\ApiController;
use Wordless\Infrastructure\Wordpress\CustomPost;
use Wordless\Infrastructure\Wordpress\CustomPostStatus;
use Wordless\Infrastructure\Wordpress\WidgetRegistrar;
use Wordless\Infrastructure\Wordpress\Menu;
use Wordless\Infrastructure\Wordpress\Schedule;
use Wordless\Infrastructure\Wordpress\Taxonomy\CustomTaxonomy;

abstract class Provider
{
    use EnqueueablesRegistration;
    use ListenersRegistration;
    use Constructors;

    public const CONFIG_KEY = 'providers';

    /**
     * @return string[]|ApiController[]
     */
    public function registerApiControllers(): array
    {
        return [];
    }

    /**
     * @return string[]|ConsoleCommand[]
     */
    public function registerCommands(): array
    {
        return [];
    }

    /**
     * @return array<string, int|bool|string|float|array>
     */
    public function registerConstants(): array
    {
        return [];
    }

    /**
     * @return string[]|Cacher[]
     */
    public function registerInternalCachers(): array
    {
        return [];
    }

    /**
     * @return string[]|Menu[]
     */
    public function registerMenus(): array
    {
        return [];
    }

    /**
     * @return string[]
     */
    public function registerMigrations(): array
    {
        return [];
    }

    /**
     * @return string[]|CustomPostStatus[]
     */
    public function registerPostStatuses(): array
    {
        return [];
    }

    /**
     * @return string[]|CustomPost[]
     */
    public function registerPostTypes(): array
    {
        return [];
    }

    /**
     * @return string[]|Provider[]
     */
    public function registerProviders(): array
    {
        return [];
    }

    /**
     * @return string[]|Schedule[]
     */
    public function registerSchedules(): array
    {
        return [];
    }

    /**
     * @return string[]|CustomTaxonomy[]
     */
    public function registerTaxonomies(): array
    {
        return [];
    }

    /**
     * @return string[]|WidgetRegistrar[]
     */
    public function registerWidgets(): array
    {
        return [];
    }
}
