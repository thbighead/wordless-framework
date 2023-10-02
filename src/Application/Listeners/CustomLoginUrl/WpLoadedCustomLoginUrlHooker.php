<?php

namespace Wordless\Application\Listeners\CustomLoginUrl;

class WpLoadedCustomLoginUrlHooker extends CustomLoginUrlHooker
{
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'load';
    /**
     * WordPress action|filter hook identification
     */
    protected const HOOK = 'wp_loaded';

    /**
     * action or filter type (defines which method will be called: add_action or add_filter)
     */
    protected const TYPE = 'action';
    private const WP_LOGIN_PHP = 'wp-login.php';

    public static function load()
    {
        if (self::canHook()) {
            global $pagenow;

            if (self::isAdminPanelRouteAndNotLoggedIn()) {
                self::redirectToFrontPageOrCustomRedirectUrl();
            }

            $request = parse_url(rawurldecode($_SERVER['REQUEST_URI']));
            if (
                self::isWpLoginPageAndRequestUriDifferentFromTrailingslashit($pagenow, $request['path'])
            ) {
                wp_safe_redirect(
                    user_trailingslashit(
                        self::newLoginSlug()) . (!empty($_SERVER['QUERY_STRING'])
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

    private static function redirectToFrontPageOrCustomRedirectUrl(): void
    {
        if ($redirect_to = self::newRedirectUrl()) {
            wp_safe_redirect("/$redirect_to");
            die();
        }

        wp_safe_redirect('/');
        die();
    }
}
