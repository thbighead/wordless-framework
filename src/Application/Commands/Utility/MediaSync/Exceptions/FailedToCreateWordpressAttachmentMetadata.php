<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Utility\MediaSync\Exceptions;

use Exception;
use Throwable;

class FailedToCreateWordpressAttachmentMetadata extends Exception
{
    public function __construct(
        public readonly int    $attachment_id,
        public readonly string $absolute_file_path,
        ?Throwable             $previous = null
    )
    {
        parent::__construct(
            "Failed to insert $this->absolute_file_path metadata of attachment id $this->attachment_id into database.",
            0,
            $previous
        );
    }
}
