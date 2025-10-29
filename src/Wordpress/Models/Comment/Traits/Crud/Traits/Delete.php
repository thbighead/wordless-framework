<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Comment\Traits\Crud\Traits;

use Wordless\Wordpress\Models\Comment\Traits\Crud\Traits\Delete\Exceptions\WpDeleteCommentFailed;

trait Delete
{
    /**
     * @return void
     * @throws WpDeleteCommentFailed
     */
    public function delete(): void
    {
        $this->callWpDeleteComment(true);
    }

    /**
     * @return void
     * @throws WpDeleteCommentFailed
     */
    public function trash(): void
    {
        $this->callWpDeleteComment();
    }

    /**
     * @param bool $force
     * @return void
     * @throws WpDeleteCommentFailed
     */
    private function callWpDeleteComment(bool $force = false): void
    {
        if (!wp_delete_comment($this->asWpComment(), $force)) {
            throw new WpDeleteCommentFailed($this, $force);
        }
    }
}
