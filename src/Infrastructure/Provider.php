<?php

namespace Wordless\Infrastructure;

use Wordless\Infrastructure\Migration\Script;
use Wordless\Infrastructure\Wordpress\ApiController;
use Wordless\Infrastructure\Wordpress\CustomPost;
use Wordless\Infrastructure\Wordpress\CustomTaxonomy;
use Wordless\Infrastructure\Wordpress\Listener;
use Wordless\Infrastructure\Wordpress\Menu;
use Wordless\Wordpress\Models\Page;

abstract class Provider
{
    /**
     * @return ApiController[]
     */
    public static function registerApiControllers(): array
    {
        return [];
    }

    /**
     * @return ConsoleCommand[]
     */
    public static function registerCommand(): array
    {
        return [];
    }

    public static function registerFrontPage(): ?Page
    {
        return null;
    }

    /**
     * @return Listener[]
     */
    public static function registerListeners(): array
    {
        return [];
    }

    /**
     * @return Menu[]
     */
    public static function registerMenus(): array
    {
        return [];
    }

    /**
     * @return Script[]
     */
    public static function registerMigration(): array
    {
        return [];
    }

    /**
     * @return CustomPost[]
     */
    public static function registerPostTypes(): array
    {
        return [];
    }

    /**
     * @return CustomTaxonomy[]
     */
    public static function registerTaxonomies(): array
    {
        return [];
    }

    public static function unregisterActionListeners(): array
    {
        return [];
    }

    public static function unregisterFilterListeners(): array
    {
        return [];
    }
}
