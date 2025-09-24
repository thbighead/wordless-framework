<?php declare(strict_types=1);

namespace Wordless\Application\Listeners;

use Wordless\Core\Bootstrapper;
use Wordless\Core\Bootstrapper\Traits\Entities\Exceptions\FailedToRegisterWordlessEntity;
use Wordless\Infrastructure\Wordpress\Hook\Contracts\ActionHook;
use Wordless\Infrastructure\Wordpress\Listener\ActionListener;
use Wordless\Wordpress\Hook\Enums\Action;

class RegisterEntities extends ActionListener
{
    protected static function hook(): ActionHook
    {
        return Action::init;
    }

    /**
     * @return void
     * @throws FailedToRegisterWordlessEntity
     */
    public static function register(): void
    {
        Bootstrapper::registerEntities();
    }
}
