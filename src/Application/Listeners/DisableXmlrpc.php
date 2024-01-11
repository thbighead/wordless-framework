<?php declare(strict_types=1);

namespace Wordless\Application\Listeners;

use Wordless\Infrastructure\Wordpress\Listener\FilterListener;
use Wordless\Wordpress\Hook\Contracts\FilterHook;
use Wordless\Wordpress\Hook\Enums\Filter;

class DisableXmlrpc extends FilterListener
{
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'disable';

    public static function disable(): bool
    {
        return false;
    }

    protected static function hook(): FilterHook
    {
        return Filter::xmlrpc_enabled;
    }
}
