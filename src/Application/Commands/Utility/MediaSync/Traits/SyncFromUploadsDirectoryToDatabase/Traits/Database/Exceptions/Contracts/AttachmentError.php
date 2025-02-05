<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Utility\MediaSync\Traits\SyncFromUploadsDirectoryToDatabase\Traits\Database\Exceptions\Contracts;

use ErrorException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

abstract class AttachmentError extends ErrorException
{
    abstract protected function message(): string;

    public function __construct(public readonly int $attachment_id, ?Throwable $previous = null)
    {
        parent::__construct($this->message(), ExceptionCode::intentional_interrupt->value, previous: $previous);
    }
}
