<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Utility\MediaSync\Traits\SyncFromDatabaseToUploadsDirectory\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToProcessUploadedFilepath extends RuntimeException
{
    public function __construct(public readonly string $uploaded_file_absolute_path, ?Throwable $previous = null)
    {
        parent::__construct(
            "Uploaded file $this->uploaded_file_absolute_path process failed.",
            ExceptionCode::intentional_interrupt->value,
            $previous
        );
    }
}
