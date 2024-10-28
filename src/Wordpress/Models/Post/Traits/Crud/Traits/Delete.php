<?php

namespace Wordless\Wordpress\Models\Post\Traits\Crud\Traits;

use Wordless\Wordpress\Models\Post\Traits\Crud\Traits\Delete\Exceptions\WpDeletePostFailed;
use WP_Post;

trait Delete
{
    /**
     * @return WP_Post
     * @throws WpDeletePostFailed
     */
    public function delete(): WP_Post
    {
        return $this->callWpDeletePost();
    }

    /**
     * @return WP_Post
     * @throws WpDeletePostFailed
     */
    public function trash(): WP_Post
    {
        return $this->callWpDeletePost(false);
    }

    /**
     * @param bool $force_delete
     * @return WP_Post
     * @throws WpDeletePostFailed
     */
    private function callWpDeletePost(bool $force_delete = true): WP_Post
    {
        if (!(($result = wp_delete_post($this->ID, $force_delete)) instanceof WP_Post)) {
            throw new WpDeletePostFailed($this->asWpPost());
        }

        return $result;
    }
}
