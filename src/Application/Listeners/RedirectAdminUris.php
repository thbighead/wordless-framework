<?php declare(strict_types=1);

namespace Wordless\Application\Listeners;

use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Providers\AdminCustomUrlProvider;
use Wordless\Exceptions\FailedToRetrieveConfigFromWordpressConfigFile;
use Wordless\Infrastructure\Wordpress\Hook\Contracts\ActionHook;
use Wordless\Infrastructure\Wordpress\Listener\ActionListener;
use Wordless\Wordpress\Hook\Enums\Action;

class RedirectAdminUris extends ActionListener
{
    /**
     * The public static method which shall be executed during hook.
     */
    protected const FUNCTION = 'redirectToAdminUri';

    public static function priority(): int
    {
        return 1000;
    }

    /**
     * @return void
     * @throws EmptyConfigKey
     * @throws PathNotFoundException
     */
    public static function redirectToAdminUri(): void
    {
        global $wp_rewrite;

        if (!(is_404() && $wp_rewrite->using_permalinks())) {
            return;
        }

        if (self::isRequestingAdminUri()) {
            wp_redirect(admin_url());
            exit;
        }
    }

    protected static function hook(): ActionHook
    {
        return Action::template_redirect;
    }

    /**
     * @return bool
     * @throws FailedToRetrieveConfigFromWordpressConfigFile
     */
    private static function isRequestingAdminUri(): bool
    {
        return untrailingslashit($_SERVER['REQUEST_URI']) === home_url(
                AdminCustomUrlProvider::getCustomUri(false),
                'relative'
            );
    }
}
