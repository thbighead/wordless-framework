<?php declare(strict_types=1);

namespace Wordless\Application\Listeners\CustomAdminUrl;

use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Listeners\CustomAdminUrl\Contracts\BaseListener;
use Wordless\Infrastructure\Wordpress\Hook\Contracts\ActionHook;
use Wordless\Infrastructure\Wordpress\Listener\ActionListener\Traits\Adapter as ActionListener;
use Wordless\Wordpress\Hook\Enums\Action;

class LoadCustomAdminUrl extends BaseListener
{
    use ActionListener;

    /**
     * @return void
     * @throws PathNotFoundException
     */
    public static function load(): void
    {
        if (!static::canHook()) {
            return;
        }

        if (!is_array($request = parse_url(rawurldecode($_SERVER['REQUEST_URI'])))) {
            return;
        }

        if (self::isRequestTheSameAsCustomAdminUri($request)) {
            global $pagenow;

            $pagenow = 'wp-login.php';
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
     * @param array $request
     * @return bool
     * @throws PathNotFoundException
     */
    private static function isRequestTheSameAsCustomAdminUri(array $request): bool
    {
        return isset($request['path'])
            && (untrailingslashit($request['path']) === home_url(static::newLoginSlug(), 'relative'));
    }
}
