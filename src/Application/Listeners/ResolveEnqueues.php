<?php declare(strict_types=1);

namespace Wordless\Application\Listeners;

use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Core\Bootstrapper;
use Wordless\Core\Bootstrapper\Exceptions\InvalidProviderClass;
use Wordless\Infrastructure\Wordpress\Hook\Contracts\ActionHook;
use Wordless\Infrastructure\Wordpress\Listener\ActionListener;
use Wordless\Wordpress\Hook\Enums\Action;

class ResolveEnqueues extends ActionListener
{
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'runEnqueues';

    /**
     * @return void
     * @throws InvalidProviderClass
     * @throws PathNotFoundException
     */
    public static function runEnqueues(): void
    {
        Bootstrapper::bootEnqueues();
    }

    protected static function hook(): ActionHook
    {
        return Action::wp_enqueue_scripts;
    }
}
