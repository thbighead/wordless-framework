<?php

namespace Wordless\Application\Listeners;

use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Infrastructure\Wordpress\CustomTaxonomy;
use Wordless\Infrastructure\Wordpress\Listener\ActionListener;
use Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Register\Validation\Exceptions\InvalidCustomTaxonomyName;
use Wordless\Wordpress\Hook\Contracts\ActionHook;
use Wordless\Wordpress\Hook\Enums\Action;

class BootCustomTaxonomies extends ActionListener
{
    public static function priority(): int
    {
        return 9;
    }

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

    protected static function hook(): ActionHook
    {
        return Action::init;
    }
}
