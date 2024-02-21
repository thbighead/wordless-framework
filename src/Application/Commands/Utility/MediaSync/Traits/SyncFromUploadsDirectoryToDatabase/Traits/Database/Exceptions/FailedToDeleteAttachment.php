<?php

namespace App\Commands\MediaSync\Traits\SyncFromUploadsDirectoryToDatabase\Traits\Database\Exceptions;

class FailedToDeleteAttachment extends AttachmentError
{
    protected function message(): string
    {
        return "Failed to delete attachment with id {$this->getAttachmentId()}";
    }
}
