<?php declare(strict_types=1);

namespace Wordless\Application\Listeners\CustomAdminUrl;

use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Listeners\CustomAdminUrl\Contracts\BaseListener;
use Wordless\Infrastructure\Wordpress\Listener\FilterListener\Traits\Adapter as FilterListener;
use Wordless\Wordpress\Hook\Contracts\FilterHook;
use Wordless\Wordpress\Hook\Enums\Filter;

class RedirectCustomLoginUrl extends BaseListener
{
    use FilterListener;

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
