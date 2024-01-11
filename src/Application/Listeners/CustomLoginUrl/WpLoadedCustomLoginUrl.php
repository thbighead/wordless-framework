<?php declare(strict_types=1);

namespace Wordless\Application\Listeners\CustomLoginUrl;

use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Listeners\CustomLoginUrl\Contracts\BaseListener;
use Wordless\Infrastructure\Wordpress\Listener\ActionListener\Traits\Adapter as ActionListener;
use Wordless\Wordpress\Hook\Contracts\ActionHook;
use Wordless\Wordpress\Hook\Enums\Action;

class WpLoadedCustomLoginUrl extends BaseListener
{
    use ActionListener;

    private const WP_LOGIN_PHP = 'wp-login.php';

    /**
     * @throws PathNotFoundException
     */
    public static function load(): void
    {
        if (static::canHook()) {
            global $pagenow;

            if (static::isAdminPanelRouteAndNotLoggedIn()) {
                static::redirectToFrontPageOrCustomRedirectUrl();
            }

            $request = parse_url(rawurldecode($_SERVER['REQUEST_URI']));
            if (
                static::isWpLoginPageAndRequestUriDifferentFromTrailingslashit($pagenow, $request['path'])
            ) {
                wp_safe_redirect(
                    user_trailingslashit(
                        static::newLoginSlug()) . (!empty($_SERVER['QUERY_STRING'])
                        ? '?' . $_SERVER['QUERY_STRING']
                        : ''
                    )
                );
                die;
            } elseif ($pagenow === self::WP_LOGIN_PHP) {
                global $error, $interim_login, $action, $user_login;

                @require_once ABSPATH . self::WP_LOGIN_PHP;
                die;
            }
        }
    }

    protected static function hook(): ActionHook
    {
        return Action::wp_loaded;
    }

    private static function isAdminPanelRouteAndNotLoggedIn(): bool
    {
        return
            is_admin()
            && !is_user_logged_in()
            && !defined('DOING_AJAX');
    }

    /**
     * @param $pagenow
     * @param string $path
     * @return bool
     */
    private static function isWpLoginPageAndRequestUriDifferentFromTrailingslashit($pagenow, string $path): bool
    {
        return $pagenow === self::WP_LOGIN_PHP &&
            $path !== user_trailingslashit($path);
    }

    /**
     * @throws PathNotFoundException
     */
    private static function redirectToFrontPageOrCustomRedirectUrl(): void
    {
        if ($redirect_to = static::newRedirectUrl()) {
            wp_safe_redirect("/$redirect_to");
            die();
        }

        wp_safe_redirect('/');
        die();
    }
}
