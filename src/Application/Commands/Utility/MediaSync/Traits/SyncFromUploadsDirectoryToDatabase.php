<?php

namespace App\Commands\MediaSync\Traits;

use App\Commands\MediaSync\Traits\SyncFromUploadsDirectoryToDatabase\Traits\Database;
use App\Commands\MediaSync\Traits\SyncFromUploadsDirectoryToDatabase\Traits\Database\Exceptions\FailedToDeleteAttachment;
use App\Commands\MediaSync\Traits\SyncFromUploadsDirectoryToDatabase\Traits\Database\Exceptions\FailedToRetrieveAttachmentUrl;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\ProjectPath;
use Wordless\Helpers\Str;

trait SyncFromUploadsDirectoryToDatabase
{
    use Database;

    private int $deleted_attachments_count = 0;
    private int $fixed_attachments_count = 0;

    private function isApplicationUploadsFileUrlBroken(string $media_url): bool
    {
        return !Str::startWith($media_url, $this->getUploadsBaseUrl());
    }

    /**
     * @param array $attachments
     * @return void
     * @throws FailedToDeleteAttachment
     * @throws FailedToRetrieveAttachmentUrl
     */
    private function processDatabaseAttachments(array $attachments)
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
            } catch (PathNotFoundException $exception) {
                $this->removeNotFoundAttachment($attachment->ID);
            } finally {
                $progressBar->advance();
            }
        }

        $progressBar->finish();
    }

    /**
     * @return void
     * @throws FailedToDeleteAttachment
     * @throws FailedToRetrieveAttachmentUrl
     */
    private function syncFromDatabaseToUploadsDirectory()
    {
        $this->writelnInfo('Checking database attachments...');
        $this->processDatabaseAttachments($this->getAttachments());
        $this->writelnSuccess(
            "\nFinished (deleted: $this->deleted_attachments_count), (fixed: $this->fixed_attachments_count)"
        );
    }
}
