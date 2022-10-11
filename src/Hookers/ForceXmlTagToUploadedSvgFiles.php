<?php

namespace Wordless\Hookers;

use Wordless\Abstractions\AbstractHooker;
use Wordless\Helpers\Str;

class ForceXmlTagToUploadedSvgFiles extends AbstractHooker
{
    private const UPLOAD_TEMP_PATH_KEY = 'tmp_name';

    /**
     * WordPress action|filter number of arguments accepted by function
     */
    protected const ACCEPTED_NUMBER_OF_ARGUMENTS = 1;
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'appendXmlTag';
    /**
     * WordPress action|filter hook identification
     */
    protected const HOOK = 'wp_handle_upload_prefilter';
    /**
     * action or filter type (defines which method will be called: add_action or add_filter)
     */
    protected const TYPE = 'filter';

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
}
