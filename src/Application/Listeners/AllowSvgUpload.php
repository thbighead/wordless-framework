<?php

namespace Wordless\Application\Listeners;

use Wordless\Infrastructure\Wordpress\Listener;

class AllowSvgUpload extends Listener
{
    /**
     * WordPress action|filter number of arguments accepted by function
     */
    protected const ACCEPTED_NUMBER_OF_ARGUMENTS = 1;
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'allowSvgMimeType';
    /**
     * WordPress action|filter hook identification
     */
    protected const HOOK = 'upload_mimes';
    /**
     * action or filter type (defines which method will be called: add_action or add_filter)
     */
    protected const TYPE = 'filter';

    public static function allowSvgMimeType(array $allowed_mimes): array
    {
        $allowed_mimes['svg'] = 'image/svg+xml';

        return $allowed_mimes;
    }
}
