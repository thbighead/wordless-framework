<?php

namespace Wordless\Hookers;

use Wordless\Abstractions\AbstractHooker;
use Wordless\Adapters\WordlessCustomPost;
use Wordless\Exceptions\InvalidCustomPostTypeKey;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\Config;

class BootCustomPostTypes extends AbstractHooker
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
            /** @var WordlessCustomPost $customPostTypeClassNamespace */
            $customPostTypeClassNamespace::register();
        }
    }
}
