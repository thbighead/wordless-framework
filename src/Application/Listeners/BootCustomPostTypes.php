<?php

namespace Wordless\Application\Listeners;

use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Infrastructure\Wordpress\CustomPost;
use Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register\Exceptions\CustomPostTypeRegistrationFailed;
use Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register\Traits\Validation\Exceptions\InvalidCustomPostTypeKey;
use Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register\Traits\Validation\Exceptions\ReservedCustomPostTypeKey;
use Wordless\Infrastructure\Wordpress\Hooker;

class BootCustomPostTypes extends Hooker
{
    public const HOOK_PRIORITY = 10;

    /**
     * WordPress action|filter hook identification
     */
    protected const HOOK = 'init';

    /**
     * @return void
     * @throws CustomPostTypeRegistrationFailed
     * @throws ReservedCustomPostTypeKey
     * @throws InvalidCustomPostTypeKey
     * @throws PathNotFoundException
     */
    public static function register(): void
    {
        foreach (Config::tryToGetOrDefault('custom-post-types', []) as $customPostTypeClassNamespace) {
            /** @var CustomPost $customPostTypeClassNamespace */
            $customPostTypeClassNamespace::register();
        }
    }
}
