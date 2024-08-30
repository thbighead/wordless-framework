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

    public static function addReferToScriptTag(string $tag, string $script_id, string $script_src): string
    {
        if (is_admin()
            || self::tagAlreadyHasDeferOrAsyncLoadRule($tag)
            || self::scriptIdHasInlineAfterAssociatedScript($script_id)
            || self::scriptSrcIsFromWpIncludes($script_src)) {
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
        return 3;
    }

    protected static function hook(): FilterHook
    {
        return Filter::script_loader_tag;
    }

    private static function scriptIdHasInlineAfterAssociatedScript(string $script_id): bool
    {
        return !empty(wp_scripts()->get_inline_script_data($script_id));
    }

    private static function scriptSrcIsFromWpIncludes(string $script_src): bool
    {
        return Str::beginsWith($script_src, includes_url());
    }

    private static function tagAlreadyHasDeferOrAsyncLoadRule(string $tag): bool
    {
        return (bool)preg_match('/^((?:[^"\\\\]|\\.|"(?:[^"\\\\]|\\.)*")*?)(defer|async)/', $tag);
    }
}
