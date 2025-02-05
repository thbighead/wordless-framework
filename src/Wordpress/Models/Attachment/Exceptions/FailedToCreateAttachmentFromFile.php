<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Attachment\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use WP_Error;

class FailedToCreateAttachmentFromFile extends RuntimeException
{
    public function __construct(
        readonly public WP_Error|int $insertResult,
        readonly public string       $from_absolute_filepath,
        readonly public string       $destiny_absolute_filepath,
        readonly public bool         $secure_mode,
        ?Throwable                   $previous = null
    )
    {
        $security_word = $this->secure_mode ? 'secure' : 'insecure';

        parent::__construct(
            "Failed to create a Wordpress attachment from path '$this->from_absolute_filepath' to '$this->destiny_absolute_filepath' in $security_word mode. The insert resulted in {$this->detailingErrorMessagePart()}.",
            ExceptionCode::intentional_interrupt->value,
            $previous
        );
    }

    private function detailingErrorMessagePart(): string
    {
        if ($this->insertResult instanceof WP_Error) {
            return 'the following errors: ' . implode('. ', $this->insertResult->get_error_messages());
        }

        return 'zero';
    }
}
