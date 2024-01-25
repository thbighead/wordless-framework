<?php

namespace Wordless\Application\Providers;

use Wordless\Application\Listeners\AllowSvgUpload;
use Wordless\Application\Listeners\ForceXmlTagToUploadedSvgFiles;
use Wordless\Infrastructure\Provider;

class SvgAdminUploadProvider extends Provider
{
    public function registerListeners(): array
    {
        return [
            AllowSvgUpload::class,
            ForceXmlTagToUploadedSvgFiles::class,
        ];
    }
}
