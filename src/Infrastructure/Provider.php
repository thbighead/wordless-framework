<?php

namespace Wordless\Infrastructure;

use Wordless\Application\Libraries\DesignPattern\Singleton;
use Wordless\Infrastructure\Migration\Script;
use Wordless\Infrastructure\Provider\Traits\ListenersRegistration;
use Wordless\Infrastructure\Wordpress\ApiController;
use Wordless\Infrastructure\Wordpress\CustomPost;
use Wordless\Infrastructure\Wordpress\CustomTaxonomy;
use Wordless\Infrastructure\Wordpress\Listener;
use Wordless\Infrastructure\Wordpress\Menu;
use Wordless\Wordpress\Models\Page;

abstract class Provider
{
    use ListenersRegistration;
    use Singleton;

    /**
     * @return ApiController[]
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

    public function registerFrontPage(): ?Page
    {
        return null;
    }

    /**
     * @return string[]|Menu[]
     */
    public function registerMenus(): array
    {
        return [];
    }

    /**
     * @return Script[]
     */
    public function registerMigrations(): array
    {
        return [];
    }

    /**
     * @return CustomPost[]
     */
    public function registerPostTypes(): array
    {
        return [];
    }

    /**
     * @return CustomTaxonomy[]
     */
    public function registerTaxonomies(): array
    {
        return [];
    }
}
