<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Utility\MediaSync\Traits\SyncFromDatabaseToUploadsDirectory\Traits;

use Symfony\Component\Dotenv\Exception\FormatException;
use Symfony\Component\Dotenv\Exception\PathException;
use Wordless\Application\Commands\Utility\MediaSync\Exceptions\FailedToCreateWordpressAttachment;
use Wordless\Application\Commands\Utility\MediaSync\Exceptions\FailedToCreateWordpressAttachmentMetadata;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Core\Exceptions\DotEnvNotSetException;
use WP_Error;

trait InsertToDatabase
{
    /**
     * @param string $uploaded_file_absolute_path
     * @return void
     * @throws DotEnvNotSetException
     * @throws FailedToCreateWordpressAttachment
     * @throws FailedToCreateWordpressAttachmentMetadata
     * @throws FormatException
     * @throws PathException
     * @throws PathNotFoundException
     */
    private function createAttachmentForUploadedFilepath(string $uploaded_file_absolute_path): void
    {
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
