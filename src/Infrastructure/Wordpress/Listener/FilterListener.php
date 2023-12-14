<?php

namespace Wordless\Infrastructure\Wordpress\Listener;

use Wordless\Infrastructure\Wordpress\Listener;
use Wordless\Infrastructure\Wordpress\Listener\FilterListener\Traits\Adapter;
use Wordless\Wordpress\Hook\Contracts\FilterHook;
use Wordless\Wordpress\Hook\Enums\Type;

abstract class FilterListener extends Listener
{
    use Adapter;

    abstract protected static function hook(): FilterHook;
}
