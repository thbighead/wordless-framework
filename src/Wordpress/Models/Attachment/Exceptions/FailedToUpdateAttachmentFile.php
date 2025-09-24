<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Attachment\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use Wordless\Wordpress\Models\Attachment;

class FailedToUpdateAttachmentFile extends RuntimeException
{
    public function __construct(
        public readonly Attachment $attachment,
        public readonly string $absolute_filepath,
        public readonly bool $secure_mode,
        ?Throwable $previous = null
    )
    {
        $secure_text = $this->secure_mode ? 'secure' : 'insecure';

        parent::__construct(
            "Failed to update attachment of ID {$this->attachment->id()} file to $this->absolute_filepath in $secure_text mode.",
            ExceptionCode::intentional_interrupt->value,
            $previous
        );
    }
}
