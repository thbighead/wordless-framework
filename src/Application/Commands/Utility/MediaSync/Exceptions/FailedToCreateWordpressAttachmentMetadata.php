<?php

namespace App\Commands\MediaSync\Exceptions;

use Exception;
use Throwable;

class FailedToCreateWordpressAttachmentMetadata extends Exception
{
    private string $absolute_file_path;
    private int $attachment_id;

    public function __construct(int $attachment_id, string $absolute_file_path, Throwable $previous = null)
    {
        $this->absolute_file_path = $absolute_file_path;
        $this->attachment_id = $attachment_id;

        parent::__construct(
            "Failed to insert $this->absolute_file_path metadata of attachment id $this->attachment_id into database.",
            0,
            $previous
        );
    }

    public function getAbsoluteFilePath(): string
    {
        return $this->absolute_file_path;
    }

    public function getAttachmentId(): int
    {
        return $this->attachment_id;
    }
}
