<?php declare(strict_types=1);

namespace Wordless\Application\Listeners;

use Wordless\Infrastructure\Wordpress\Hook\Contracts\ActionHook;
use Wordless\Infrastructure\Wordpress\Listener\ActionListener;
use Wordless\Wordpress\Hook\Enums\Action;

class HandleCors extends ActionListener
{
    protected static function hook(): ActionHook
    {
        return Action::send_headers;
    }
}
