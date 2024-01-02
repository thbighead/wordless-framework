<?php

namespace Wordless\Application\Listeners;

use Wordless\Application\Helpers\Str;
use Wordless\Infrastructure\Wordpress\Listener\FilterListener;
use Wordless\Wordpress\Hook\Contracts\FilterHook;
use Wordless\Wordpress\Hook\Enums\Filter;

class DeferEnqueuedScripts extends FilterListener
{
    private const DEFER_ATTRIBUTE = 'defer=\'true';
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'addReferToScriptTag';

    public static function addReferToScriptTag(string $url): string
    {
        if (is_admin() || !Str::of($url)->beforeLast('?')->endsWith('.js')) {
            return $url;
        }

        if (!Str::contains($url, self::DEFER_ATTRIBUTE)) {
            return "$url' " . self::DEFER_ATTRIBUTE;
        }

        return $url;
    }

    public static function priority(): int
    {
        return 20;
    }

    protected static function functionNumberOfArgumentsAccepted(): int
    {
        return 1;
    }

    protected static function hook(): FilterHook
    {
        return Filter::clean_url;
    }
}
