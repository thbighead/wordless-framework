<?php

namespace Wordless\Application\Hookers;

use Wordless\Application\Helpers\Config;
use Wordless\Exceptions\InvalidCustomPostTypeKey;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Infrastructure\CustomPost;
use Wordless\Infrastructure\Hooker;

class BootCustomPostTypes extends Hooker
{
    public const HOOK_PRIORITY = 10;

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
    }
}
