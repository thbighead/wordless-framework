<?php

namespace Wordless\Infrastructure;

use Wordless\Application\Libraries\DesignPattern\Singleton\Traits\Constructors;
use Wordless\Infrastructure\Migration\Script;
use Wordless\Infrastructure\Provider\Traits\EnqueueablesRegistration;
use Wordless\Infrastructure\Provider\Traits\ListenersRegistration;
use Wordless\Infrastructure\Wordpress\ApiController;
use Wordless\Infrastructure\Wordpress\CustomPost;
use Wordless\Infrastructure\Wordpress\CustomTaxonomy;
use Wordless\Infrastructure\Wordpress\Menu;
use Wordless\Wordpress\Models\Page;

abstract class Provider
{
    use EnqueueablesRegistration;
    use ListenersRegistration;
    use Constructors;

    /**
     * @return string[]|ApiController[]
     */
    public function registerApiControllers(): array
    {
        return [];
    }

    /**
     * @return ConsoleCommand[]
     */
    public function registerCommands(): array
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
     * @return string[]|CustomPost[]
     */
    public function registerPostTypes(): array
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
}
