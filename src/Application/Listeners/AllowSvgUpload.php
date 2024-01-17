<?php declare(strict_types=1);

namespace Wordless\Application\Listeners;


use Wordless\Infrastructure\Wordpress\Hook\Contracts\FilterHook;
use Wordless\Infrastructure\Wordpress\Listener\FilterListener;
use Wordless\Wordpress\Hook\Enums\Filter;

class AllowSvgUpload extends FilterListener
{
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'allowSvgMimeType';

    public static function allowSvgMimeType(array $allowed_mimes): array
    {
        $allowed_mimes['svg'] = 'image/svg+xml';

        return $allowed_mimes;
    }

    protected static function functionNumberOfArgumentsAccepted(): int
    {
        return 1;
    }

    protected static function hook(): FilterHook
    {
        return Filter::upload_mimes;
    }
}
