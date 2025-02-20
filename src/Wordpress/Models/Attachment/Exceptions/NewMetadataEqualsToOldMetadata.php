<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Attachment\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class NewMetadataEqualsToOldMetadata extends RuntimeException
{
    public function __construct(public readonly int $attachment_id, public readonly array $new_metadata_tried, ?Throwable $previous = null)
    {
        parent::__construct(
            "Tried to update attachment's with id $this->attachment_id metadata with the same values of its original metadata",
            ExceptionCode::caught_internally->value,
            $previous
        );
    }
}
