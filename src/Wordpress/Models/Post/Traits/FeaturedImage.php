<?php

namespace Wordless\Wordpress\Models\Post\Traits;

use Wordless\Wordpress\Models\Attachment;
use Wordless\Wordpress\Models\Post\Exceptions\InitializingModelWithWrongPostType;
use Wordless\Wordpress\Models\PostType\Exceptions\PostTypeNotRegistered;

trait FeaturedImage
{
    protected Attachment|false|null $featuredImage = false;

    /**
     * @param bool $with_acfs
     * @return Attachment|null
     * @throws InitializingModelWithWrongPostType
     * @throws PostTypeNotRegistered
     */
    public function getFeaturedImage(bool $with_acfs = false): ?Attachment
    {
        if ($this->featuredImage !== false) {
            return $this->featuredImage;
        }

        $featured_image_id = get_post_thumbnail_id($this->asWpPost());

        return $this->featuredImage =
            ($featured_image_id === false ? null : Attachment::get($featured_image_id, $with_acfs));
    }

    /**
     * @param bool $keep_featured_image_loaded
     * @return int|null
     * @throws InitializingModelWithWrongPostType
     * @throws PostTypeNotRegistered
     */
    public function getFeaturedImageId(bool $keep_featured_image_loaded = false): ?int
    {
        if ($this->featuredImage === null) {
            return null;
        }

        if ($this->featuredImage instanceof Attachment) {
            return $this->featuredImage->ID;
        }

        if (($featured_image_id = get_post_thumbnail_id($this->asWpPost())) === false) {
            $featured_image_id = null;
        }

        if ($keep_featured_image_loaded) {
            $this->featuredImage = is_null($featured_image_id) ? null : Attachment::get($featured_image_id);
        }

        return $featured_image_id;
    }
}
