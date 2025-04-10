<?php declare(strict_types=1);

namespace Wordless\Application\Listeners;

use Wordless\Application\Helpers\DirectoryFiles;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToCreateDirectory;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToGetDirectoryPermissions;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToGetFileContent;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToPutFileContent;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Infrastructure\Enums\MimeType;
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

    /**
     * @param array $upload
     * @return array
     * @throws FailedToCreateDirectory
     * @throws FailedToGetDirectoryPermissions
     * @throws FailedToGetFileContent
     * @throws FailedToPutFileContent
     * @throws PathNotFoundException
     */
    public static function appendXmlTag(array $upload): array
    {
        if (($upload['type'] ?? null) !== MimeType::image_svgxml->value) {
            return $upload;
        }

        $contents = DirectoryFiles::getFileContent($upload[self::UPLOAD_TEMP_PATH_KEY]);

        if (!Str::contains($contents, '<?xml')) {
            DirectoryFiles::createFileAt(
                $upload[self::UPLOAD_TEMP_PATH_KEY],
                "<?xml version=\"1.0\" encoding=\"UTF-8\"?>$contents",
                false
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
