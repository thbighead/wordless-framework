<?php declare(strict_types=1);

namespace Wordless\Application\Listeners;

use Wordless\Application\Helpers\Environment;
use Wordless\Application\Helpers\Environment\Exceptions\CannotResolveEnvironmentGet;
use Wordless\Application\Listeners\EnableCsp\Exceptions\FailedToEnableCSP;
use Wordless\Infrastructure\Http\Security\Csp;
use Wordless\Infrastructure\Http\Security\Csp\Exceptions\FailedToSendCspHeaders;
use Wordless\Infrastructure\Wordpress\Hook\Contracts\ActionHook;
use Wordless\Infrastructure\Wordpress\Listener\ActionListener;
use Wordless\Wordpress\Hook\Enums\Action;

class EnableCsp extends ActionListener
{
    /**
     * The public static method which shall be executed during hook.
     */
    protected const FUNCTION = 'enable';

    /**
     * Solving insecure cookies (https://rainastudio.com/enable-secure-cookie-setting/)
     * @return void
     * @throws FailedToEnableCSP
     */
    public static function enable(): void
    {
        try {
            if (Environment::get('WORDLESS_CSP', false)) {
                Csp::enable();
            }
        } catch (FailedToSendCspHeaders|CannotResolveEnvironmentGet $exception) {
            throw new FailedToEnableCSP($exception);
        }
    }

    protected static function hook(): ActionHook
    {
        return Action::init;
    }
}
