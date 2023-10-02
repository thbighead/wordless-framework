<?php

namespace Wordless\Application\Listeners;

use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Infrastructure\Wordpress\Listener;
use Wordless\Infrastructure\Wordpress\CustomTaxonomy;
use Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Register\Validation\Exceptions\InvalidCustomTaxonomyName;

class BootCustomTaxonomies extends Listener
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
     * @throws InvalidCustomTaxonomyName
     */
    public static function register(): void
    {
        foreach (Config::tryToGetOrDefault('custom-taxonomies', []) as $customTaxonomyClassNamespace) {
            /** @var CustomTaxonomy $customTaxonomyClassNamespace */
            $customTaxonomyClassNamespace::register();
        }
    }
}
