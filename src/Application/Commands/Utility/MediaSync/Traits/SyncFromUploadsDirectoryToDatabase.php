<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Utility\MediaSync\Traits;

use Symfony\Component\Dotenv\Exception\FormatException;
use Symfony\Component\Dotenv\Exception\PathException;
use Wordless\Application\Commands\Utility\MediaSync\Traits\SyncFromUploadsDirectoryToDatabase\Traits\Database;
use Wordless\Application\Commands\Utility\MediaSync\Traits\SyncFromUploadsDirectoryToDatabase\Traits\Database\Exceptions\FailedToDeleteAttachment;
use Wordless\Application\Commands\Utility\MediaSync\Traits\SyncFromUploadsDirectoryToDatabase\Traits\Database\Exceptions\FailedToRetrieveAttachmentUrl;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Core\Exceptions\DotEnvNotSetException;
use WP_Post;

trait SyncFromUploadsDirectoryToDatabase
{
    use Database;

    private int $deleted_attachments_count = 0;
    private int $fixed_attachments_count = 0;

    /**
     * @param string $media_url
     * @return bool
     * @throws DotEnvNotSetException
     * @throws FormatException
     * @throws PathException
     */
    private function isApplicationUploadsFileUrlBroken(string $media_url): bool
    {
        return !Str::startWith($media_url, $this->getUploadsBaseUrl());
    }

    /**
     * @param WP_Post[] $attachments
     * @return void
     * @throws DotEnvNotSetException
     * @throws FailedToDeleteAttachment
     * @throws FailedToRetrieveAttachmentUrl
     * @throws FormatException
     * @throws PathException
     */
    private function processDatabaseAttachments(array $attachments): void
    {
        $progressBar = $this->initializeProgressBar(count($attachments));

        foreach ($attachments as $attachment) {
            $relative_path = Str::after(
                $attachment_url = $this->getAttachmentUrl($attachment->ID),
                $this->getUploadsBaseUrl()
            );

            try {
                $full_path = ProjectPath::wpUploads($relative_path);

                if ($this->isApplicationUploadsFileUrlBroken($attachment_url)) {
                    $this->fixAttachmentFileRelativePath($attachment->ID, $relative_path);
                }

                $this->already_synchronized_attachments[$full_path] = $relative_path;
            } catch (PathNotFoundException) {
                $this->removeNotFoundAttachment($attachment->ID);
            } finally {
                $progressBar->advance();
            }
        }

        $progressBar->finish();
    }

    /**
     * @return void
     * @throws DotEnvNotSetException
     * @throws FailedToDeleteAttachment
     * @throws FailedToRetrieveAttachmentUrl
     * @throws FormatException
     * @throws PathException
     */
    private function syncFromDatabaseToUploadsDirectory(): void
    {
        $this->writelnInfo('Checking database attachments...');
        $this->processDatabaseAttachments($this->getAttachments());
        $this->writelnSuccess(
            "\nFinished (deleted: $this->deleted_attachments_count), (fixed: $this->fixed_attachments_count)"
        );
    }
}
