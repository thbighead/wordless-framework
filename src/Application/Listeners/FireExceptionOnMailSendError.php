<?php declare(strict_types=1);

namespace Wordless\Application\Listeners;

use Wordless\Application\Listeners\FireExceptionOnMailSendError\Exceptions\FailedToSendMailMessage;
use Wordless\Infrastructure\Wordpress\Hook\Contracts\ActionHook;
use Wordless\Infrastructure\Wordpress\Listener\ActionListener;
use Wordless\Wordpress\Hook\Enums\Action;
use WP_Error;

class FireExceptionOnMailSendError extends ActionListener
{
    protected const FUNCTION = 'interruptWhenFail';

    protected static function hook(): ActionHook
    {
        return Action::wp_mail_failed;
    }

    public static function interruptWhenFail(WP_Error $error): void
    {
        throw new FailedToSendMailMessage($error);
    }

    protected static function functionNumberOfArgumentsAccepted(): int
    {
        return 1;
    }
}
