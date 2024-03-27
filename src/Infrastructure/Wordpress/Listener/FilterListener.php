<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Listener;

use Wordless\Infrastructure\Wordpress\Hook\Contracts\FilterHook;
use Wordless\Infrastructure\Wordpress\Listener;
use Wordless\Infrastructure\Wordpress\Listener\FilterListener\Traits\Adapter;

abstract class FilterListener extends Listener
{
    use Adapter;

    abstract protected static function hook(): FilterHook;
}
