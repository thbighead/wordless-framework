<?php declare(strict_types=1);

namespace Wordless\Application\Listeners;

use Wordless\Application\Helpers\Str;
use Wordless\Infrastructure\Wordpress\Hook\Contracts\FilterHook;
use Wordless\Infrastructure\Wordpress\Listener\FilterListener;
use Wordless\Wordpress\Hook\Enums\Filter;

class ForceXmlTagToUploadedSvgFiles extends FilterListener
{
    private const UPLOAD_TEMP_PATH_KEY = 'tmp_name';
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'appendXmlTag';


    public static function appendXmlTag(array $upload): array
    {
        if (($upload['type'] ?? null) !== 'image/svg+xml') {
            return $upload;
        }

        $contents = file_get_contents($upload[self::UPLOAD_TEMP_PATH_KEY]);

        if (!Str::contains($contents, '<?xml')) {
            file_put_contents(
                $upload[self::UPLOAD_TEMP_PATH_KEY],
                "<?xml version=\"1.0\" encoding=\"UTF-8\"?>$contents"
            );
        }

        return $upload;
    }

    protected static function functionNumberOfArgumentsAccepted(): int
    {
        return 1;
    }

    protected static function hook(): FilterHook
    {
        return Filter::wp_handle_upload_prefilter;
    }
}
