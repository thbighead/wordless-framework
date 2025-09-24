<?php declare(strict_types=1);

namespace Wordless\Application\Listeners;

use Wordless\Core\Bootstrapper;
use Wordless\Core\Bootstrapper\Traits\MainPlugin\Exceptions\FailedToBootEnqueueables;
use Wordless\Infrastructure\Wordpress\Hook\Contracts\ActionHook;
use Wordless\Infrastructure\Wordpress\Listener\ActionListener;
use Wordless\Wordpress\Hook\Enums\Action;

class ResolveFrontendEnqueues extends ActionListener
{
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'runEnqueues';

    /**
     * @return void
     * @throws FailedToBootEnqueueables
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
