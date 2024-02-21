<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Utility\MediaSync\Traits\SyncFromUploadsDirectoryToDatabase\Traits\Database\Exceptions;

use Wordless\Application\Commands\Utility\MediaSync\Traits\SyncFromUploadsDirectoryToDatabase\Traits\Database\Exceptions\Contracts\AttachmentError;

class FailedToRetrieveAttachmentUrl extends AttachmentError
{
    protected function message(): string
    {
        return "Failed to retrieve the URL of attachment with id $this->attachment_id";
    }
}
