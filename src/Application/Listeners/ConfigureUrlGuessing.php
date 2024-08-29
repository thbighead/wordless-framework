<?php

namespace Wordless\Application\Listeners;

use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Infrastructure\Wordpress\Hook\Contracts\FilterHook;
use Wordless\Infrastructure\Wordpress\Listener\FilterListener;
use Wordless\Wordpress\Hook\Enums\Filter;

class ConfigureUrlGuessing extends FilterListener
{
    final public const CONFIG_KEY_STOP_URL_GUESSING = 'stop_url_guessing';
    /**
     * The public static method which shall be executed during hook.
     */
    protected const FUNCTION = 'configure404ResponseGuessing';

    /**
     * @return bool
     * @throws PathNotFoundException
     */
    public static function configure404ResponseGuessing(): bool
    {
        return Config::wordpress(self::CONFIG_KEY_STOP_URL_GUESSING, true);
    }

    protected static function hook(): FilterHook
    {
        return Filter::do_redirect_guess_404_permalink;
    }
}
