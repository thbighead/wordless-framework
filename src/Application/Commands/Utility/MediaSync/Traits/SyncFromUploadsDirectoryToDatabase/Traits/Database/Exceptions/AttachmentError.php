<?php

namespace App\Commands\MediaSync\Traits\SyncFromUploadsDirectoryToDatabase\Traits\Database\Exceptions;

use Exception;
use Throwable;

abstract class AttachmentError extends Exception
{
    private int $attachment_id;

    abstract protected function message(): string;

    public function __construct(int $attachment_id, Throwable $previous = null)
    {
        $this->attachment_id = $attachment_id;

        parent::__construct($this->message(), 0, $previous);
    }

    public function getAttachmentId(): int
    {
        return $this->attachment_id;
    }
}
