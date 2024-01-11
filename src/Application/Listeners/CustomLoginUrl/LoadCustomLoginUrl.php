<?php declare(strict_types=1);

namespace Wordless\Application\Listeners\CustomLoginUrl;

use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Listeners\CustomLoginUrl\Contracts\BaseListener;
use Wordless\Infrastructure\Wordpress\Listener\ActionListener\Traits\Adapter as ActionListener;
use Wordless\Wordpress\Hook\Contracts\ActionHook;
use Wordless\Wordpress\Hook\Enums\Action;

class LoadCustomLoginUrl extends BaseListener
{
    use ActionListener;

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
