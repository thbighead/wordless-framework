<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Attachment\DTO;

readonly class WpInsertAttachmentResultDTO
{
    public function __construct(public int $attachment_id, public string $wp_uploads_filepath)
    {
    }
}
