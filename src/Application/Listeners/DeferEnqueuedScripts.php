<?php declare(strict_types=1);

namespace Wordless\Application\Listeners;

use Wordless\Application\Helpers\Str;
use Wordless\Infrastructure\Wordpress\Hook\Contracts\FilterHook;
use Wordless\Infrastructure\Wordpress\Listener\FilterListener;
use Wordless\Wordpress\Hook\Enums\Filter;

class DeferEnqueuedScripts extends FilterListener
{
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'addReferToScriptTag';

    public static function addReferToScriptTag(string $tag): string
    {
        if (is_admin() || self::tagAlreadyHasDeferOrAsyncLoadRule($tag)) {
            return $tag;
        }

        return Str::replace($tag, ' src=', ' defer src=');
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
        return Filter::script_loader_tag;
    }

    private static function tagAlreadyHasDeferOrAsyncLoadRule(string $tag): bool
    {
        return (bool)preg_match('/^((?:[^"\\\\]|\\.|"(?:[^"\\\\]|\\.)*")*?)(defer|async)/', $tag);
    }
}
