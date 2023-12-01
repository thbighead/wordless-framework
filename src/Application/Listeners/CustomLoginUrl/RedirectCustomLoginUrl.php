<?php

namespace Wordless\Application\Listeners\CustomLoginUrl;

use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Listeners\CustomLoginUrl\Traits\Common;
use Wordless\Infrastructure\Wordpress\Listener\FilterListener;
use Wordless\Wordpress\Hook\Contracts\FilterHook;
use Wordless\Wordpress\Hook\Enums\Filter;

class RedirectCustomLoginUrl extends FilterListener
{
    use Common;

    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'load';

    /**
     * @param string $location
     * @return string
     * @throws PathNotFoundException
     */
    public static function load(string $location): string
    {
        if (static::canHook()) {
            return static::filterWpLoginPhp($location);
        }

        return $location;
    }

    protected static function functionNumberOfArgumentsAccepted(): int
    {
        return 1;
    }

    protected static function hook(): FilterHook
    {
        return Filter::wp_redirect;
    }
}
