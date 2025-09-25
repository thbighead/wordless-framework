<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Utility\MediaSync\Traits\SyncFromDatabaseToUploadsDirectory\Traits;

use Wordless\Application\Commands\Utility\MediaSync\Exceptions\FailedToCreateWordpressAttachment;
use Wordless\Application\Commands\Utility\MediaSync\Exceptions\FailedToCreateWordpressAttachmentMetadata;
use Wordless\Application\Commands\Utility\MediaSync\Traits\SyncFromDatabaseToUploadsDirectory\Traits\InsertToDatabase\Exceptions\FailedToCreateAttachmentForUploadedFilepath;
use Wordless\Application\Helpers\Environment\Exceptions\CannotResolveEnvironmentGet;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use WP_Error;

trait InsertToDatabase
{
    /**
     * @param string $uploaded_file_absolute_path
     * @return void
     * @throws FailedToCreateAttachmentForUploadedFilepath
     * @throws FailedToCreateWordpressAttachment
     */
    private function createAttachmentForUploadedFilepath(string $uploaded_file_absolute_path): void
    {
        try {
            $relative_path = Str::after(
                $uploaded_file_absolute_path,
                Str::finishWith($this->getUploadsDirectoryAbsolutePath(), '/')
            );

            $attachment_id = wp_insert_attachment([
                'guid' => "{$this->getUploadsBaseUrl()}$relative_path",
                'post_mime_type' => mime_content_type($uploaded_file_absolute_path),
                'post_title' => $this->extractFilenameFromAbsolutePath($uploaded_file_absolute_path),
                'post_content' => '',
                'post_status' => 'inherit',
            ], $relative_path);

            if (!$this->hasAttachmentCreationSucceeded($attachment_id)) {
                throw new FailedToCreateWordpressAttachment($uploaded_file_absolute_path);
            }

            $this->createAttachmentMetadataForUploadedFilepath($attachment_id, $uploaded_file_absolute_path);
        } catch (CannotResolveEnvironmentGet
        |FailedToCreateWordpressAttachmentMetadata
        |PathNotFoundException $exception) {
            throw new FailedToCreateAttachmentForUploadedFilepath($uploaded_file_absolute_path, $exception);
        }
    }

    /**
     * @param int $attachment_id
     * @param string $uploaded_file_absolute_path
     * @return void
     * @throws FailedToCreateWordpressAttachmentMetadata
     * @throws PathNotFoundException
     */
    private function createAttachmentMetadataForUploadedFilepath(
        int    $attachment_id,
        string $uploaded_file_absolute_path
    ): void
    {
        require_once ProjectPath::wpCore('wp-admin/includes/image.php');

        if (!is_array(wp_generate_attachment_metadata($attachment_id, $uploaded_file_absolute_path))) {
            throw new FailedToCreateWordpressAttachmentMetadata($attachment_id, $uploaded_file_absolute_path);
        }
    }

    private function hasAttachmentCreationSucceeded(int|WP_Error $attachment_creation_result): bool
    {
        return $attachment_creation_result !== 0 && !($attachment_creation_result instanceof WP_Error);
    }
}
