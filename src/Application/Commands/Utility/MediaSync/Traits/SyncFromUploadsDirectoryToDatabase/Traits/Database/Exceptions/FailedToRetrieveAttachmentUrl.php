<?php

namespace App\Commands\MediaSync\Traits\SyncFromUploadsDirectoryToDatabase\Traits\Database\Exceptions;

class FailedToRetrieveAttachmentUrl extends AttachmentError
{
    protected function message(): string
    {
        return "Failed to retrieve the URL of attachment with id {$this->getAttachmentId()}";
    }
}
