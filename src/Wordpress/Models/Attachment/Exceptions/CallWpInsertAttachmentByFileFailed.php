<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Attachment\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use Wordless\Wordpress\Models\PostStatus;
use Wordless\Wordpress\Models\PostStatus\Enums\StandardStatus;

class CallWpInsertAttachmentByFileFailed extends RuntimeException
{
    public function __construct(
        public readonly string                        $absolute_filepath,
        public readonly int|PostStatus|StandardStatus $status_or_attachment_id,
        public readonly bool                          $secure_mode,
        ?Throwable                                    $previous = null
    )
    {
        $secure_text = $this->secure_mode ? 'secure' : 'insecure';
        $status_or_attachment_id_text = is_int($this->status_or_attachment_id)
            ? "attachment ID $this->status_or_attachment_id"
            : 'status ' . ($this->status_or_attachment_id->value ?? $this->status_or_attachment_id->name);

        parent::__construct(
            "Could not call wp_insert_attachment function for file $this->absolute_filepath in $secure_text mode with $status_or_attachment_id_text",
            ExceptionCode::intentional_interrupt->value,
            $previous
        );
    }
}
