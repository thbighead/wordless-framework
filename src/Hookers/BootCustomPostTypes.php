<?php

namespace Wordless\Hookers;

use Wordless\Abstractions\Hooker;
use Wordless\Adapters\CustomPost;
use Wordless\Exceptions\InvalidCustomPostTypeKey;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\Config;
use Wordless\Helpers\Environment;

class BootCustomPostTypes extends Hooker
{
    /**
     * WordPress action|filter hook identification
     */
    protected const HOOK = 'init';

    /**
     * @return void
     * @throws InvalidCustomPostTypeKey
     * @throws PathNotFoundException
     */
    public static function register()
    {
        foreach (Config::tryToGetOrDefault('custom-post-types', []) as $customPostTypeClassNamespace) {
            /** @var CustomPost $customPostTypeClassNamespace */
            $customPostTypeClassNamespace::register();
        }

        if (Environment::isLocal()) {
            flush_rewrite_rules();
        }
    }
}
