<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Listener;

use Wordless\Infrastructure\Wordpress\Hook\Contracts\ActionHook;
use Wordless\Infrastructure\Wordpress\Listener;
use Wordless\Infrastructure\Wordpress\Listener\ActionListener\Traits\Adapter;

abstract class ActionListener extends Listener
{
    use Adapter;

    abstract protected static function hook(): ActionHook;
}
