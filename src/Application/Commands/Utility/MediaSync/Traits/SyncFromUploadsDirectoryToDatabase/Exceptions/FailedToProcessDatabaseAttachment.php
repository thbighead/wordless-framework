<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Utility\MediaSync\Traits\SyncFromUploadsDirectoryToDatabase\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use WP_Post;

class FailedToProcessDatabaseAttachment extends RuntimeException
{
    public function __construct(public readonly WP_Post $attachment, ?Throwable $previous = null)
    {
        parent::__construct(
            "Failed to process attachment '{$this->attachment->post_title}' (ID: {$this->attachment->ID})",
            ExceptionCode::intentional_interrupt->value,
            $previous
        );
    }
}
