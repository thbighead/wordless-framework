<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Utility\MediaSync\Traits\SyncFromDatabaseToUploadsDirectory\Traits\InsertToDatabase\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToCreateAttachmentForUploadedFilepath extends RuntimeException
{
    public function __construct(public readonly string $uploaded_file_absolute_path, ?Throwable $previous = null)
    {
        parent::__construct(
            "Could not create Wordpress attachment into database based in $this->uploaded_file_absolute_path file.",
            ExceptionCode::intentional_interrupt->value,
            $previous
        );
    }
}
