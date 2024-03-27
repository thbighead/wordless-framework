<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Utility\MediaSync\Traits\SyncFromUploadsDirectoryToDatabase\Traits;

use Wordless\Application\Commands\Utility\MediaSync\Traits\SyncFromUploadsDirectoryToDatabase\Traits\Database\Exceptions\FailedToDeleteAttachment;
use Wordless\Application\Commands\Utility\MediaSync\Traits\SyncFromUploadsDirectoryToDatabase\Traits\Database\Exceptions\FailedToRetrieveAttachmentUrl;
use WP_Post;
use WP_Query;

trait Database
{
    private function fixAttachmentFileRelativePath(int $attachment_id, string $relative_path): void
    {
        wp_update_attachment_metadata($attachment_id, ['file' => $relative_path]);
        $this->fixed_attachments_count++;
    }

    /**
     * @return WP_Post[]
     */
    private function getAttachments(): array
    {
        $post_query = new WP_Query([
            'post_type' => 'attachment',
            'post_status' => 'inherit',
            'posts_per_page' => -1,
        ]);

        return $post_query->get_posts();
    }

    /**
     * @param int $attachment_id
     * @return string
     * @throws FailedToRetrieveAttachmentUrl
     */
    private function getAttachmentUrl(int $attachment_id): string
    {
        if (!is_string($attachment_url = wp_get_attachment_url($attachment_id)) || empty($attachment_url)) {
            throw new FailedToRetrieveAttachmentUrl($attachment_id);
        }

        return $attachment_url;
    }

    /**
     * @param int $attachment_id
     * @return void
     * @throws FailedToDeleteAttachment
     */
    private function removeNotFoundAttachment(int $attachment_id): void
    {
        if (is_null($deletion_result = wp_delete_attachment($attachment_id)) || $deletion_result === false) {
            throw new FailedToDeleteAttachment($attachment_id);
        }

        $this->deleted_attachments_count++;
    }
}
