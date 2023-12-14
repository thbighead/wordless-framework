<?php

namespace Wordless\Infrastructure\Wordpress\Listener;

use Wordless\Infrastructure\Wordpress\Listener;
use Wordless\Infrastructure\Wordpress\Listener\ActionListener\Traits\Adapter;
use Wordless\Wordpress\Hook\Contracts\ActionHook;

abstract class ActionListener extends Listener
{
    use Adapter;

    abstract protected static function hook(): ActionHook;
}
