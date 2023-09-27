<?php

namespace Wordless\Application\Listeners;

use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Infrastructure\Wordpress\Hooker;
use Wordless\Infrastructure\Wordpress\CustomTaxonomy;

class BootCustomTaxonomies extends Hooker
{
    /**
     * WordPress action|filter hook identification
     */
    protected const HOOK = 'init';
    /**
     * WordPress action|filter hook priority
     */
    protected const HOOK_PRIORITY = BootCustomPostTypes::HOOK_PRIORITY - 1;

    /**
     * @return void
     * @throws PathNotFoundException
     */
    public static function register()
    {
        foreach (Config::tryToGetOrDefault('custom-taxonomies', []) as $customTaxonomyClassNamespace) {
            /** @var CustomTaxonomy $customTaxonomyClassNamespace */
            $customTaxonomyClassNamespace::register();
        }
    }
}
