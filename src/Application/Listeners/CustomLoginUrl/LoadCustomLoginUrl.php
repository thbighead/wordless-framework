<?php

namespace Wordless\Application\Listeners\CustomLoginUrl;

use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Listeners\CustomLoginUrl\Traits\Common;
use Wordless\Infrastructure\Wordpress\Listener\ActionListener;
use Wordless\Wordpress\Hook\Contracts\ActionHook;
use Wordless\Wordpress\Hook\Enums\Action;

class LoadCustomLoginUrl extends ActionListener
{
    use Common;

    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'load';

    /**
     * @throws PathNotFoundException
     */
    public static function load()
    {
        if (static::canHook()) {
            global $pagenow;
            $request = parse_url(rawurldecode($_SERVER['REQUEST_URI']));

            if (static::isRequestTheSameAsCustomLogin($request)) {
                $pagenow = 'wp-login.php';
            }
        }
    }

    public static function priority(): int
    {
        return 1;
    }

    protected static function hook(): ActionHook
    {
        return Action::plugins_loaded;
    }

    /**
     * @throws PathNotFoundException
     */
    private static function isRequestTheSameAsCustomLogin($request): bool
    {
        return isset($request['path'])
            && (untrailingslashit($request['path']) === home_url(static::newLoginSlug(), 'relative'));
    }
}
