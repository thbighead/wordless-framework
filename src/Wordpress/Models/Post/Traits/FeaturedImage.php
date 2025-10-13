<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Post\Traits;

use Wordless\Wordpress\Models\Attachment;
use Wordless\Wordpress\Models\Post\Exceptions\InitializingModelWithWrongPostType;
use Wordless\Wordpress\Models\Post\Traits\Crud\FeaturedImage\Exceptions\FailedToGetPostFeaturedImage;
use Wordless\Wordpress\Models\Post\Traits\Crud\FeaturedImage\Exceptions\FailedToSetPostFeaturedImage;
use Wordless\Wordpress\Models\PostType\Exceptions\PostTypeNotRegistered;

trait FeaturedImage
{
    protected Attachment|false|null $featuredImage = false;

    /**
     * @param Attachment|int $attachment
     * @return $this
     * @throws FailedToSetPostFeaturedImage
     * @throws InitializingModelWithWrongPostType
     * @throws PostTypeNotRegistered
     */
    public function setFeaturedImage(Attachment|int $attachment): static
    {
        if ($this->getFeaturedImageId() === (is_int($attachment) ? $attachment : $attachment->id())) {
            return $this;
        }

        if (set_post_thumbnail($this->id(), $attachment) === false) {
            throw new FailedToSetPostFeaturedImage($this, $attachment);
        }

        return $this;
    }

    /**
     * @return Attachment|null
     * @throws FailedToGetPostFeaturedImage
     * @throws InitializingModelWithWrongPostType
     * @throws PostTypeNotRegistered
     */
    public function getFeaturedImage(): ?Attachment
    {
        if ($this->featuredImage !== false) {
            return $this->featuredImage;
        }

        if (($featured_image_id = get_post_thumbnail_id($this->asWpPost())) === false) {
            throw new FailedToGetPostFeaturedImage($this);
        }

        return $this->featuredImage =
            ($featured_image_id === 0 ? null : Attachment::make($featured_image_id));
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
            $this->featuredImage = is_null($featured_image_id) ? null : Attachment::make($featured_image_id);
        }

        return $featured_image_id;
    }
}
