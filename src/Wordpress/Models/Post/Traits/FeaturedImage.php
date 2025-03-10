<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Post\Traits;

use Wordless\Wordpress\Models\Attachment;
use Wordless\Wordpress\Models\Post\Exceptions\InitializingModelWithWrongPostType;
use Wordless\Wordpress\Models\Post\Traits\Crud\FeaturedImage\Exceptions\FailedToGetPostFeaturedImage;
use Wordless\Wordpress\Models\Post\Traits\Crud\FeaturedImage\Exceptions\FailedToSetPostFeaturedImage;
use Wordless\Wordpress\Models\PostType\Exceptions\PostTypeNotRegistered;
use Wordless\Wordpress\Models\Traits\WithAcfs\Exceptions\InvalidAcfFunction;

trait FeaturedImage
{
    protected Attachment|false|null $featuredImage = false;

    /**
     * @param int $attachment_id
     * @return $this
     * @throws FailedToSetPostFeaturedImage
     */
    public function createOrUpdateFeaturedImage(int $attachment_id): static
    {
        if (set_post_thumbnail($this->id(), $attachment_id) === false) {
            throw new FailedToSetPostFeaturedImage($this, $attachment_id);
        }

        return $this;
    }

    /**
     * @param bool $with_acfs
     * @return Attachment|null
     * @throws FailedToGetPostFeaturedImage
     * @throws InitializingModelWithWrongPostType
     * @throws InvalidAcfFunction
     * @throws PostTypeNotRegistered
     */
    public function getFeaturedImage(bool $with_acfs = false): ?Attachment
    {
        if ($this->featuredImage !== false) {
            return $this->featuredImage;
        }

        if (($featured_image_id = get_post_thumbnail_id($this->asWpPost())) === false) {
            throw new FailedToGetPostFeaturedImage($this);
        }

        return $this->featuredImage =
            ($featured_image_id === 0 ? null : Attachment::get($featured_image_id, $with_acfs));
    }

    /**
     * @param bool $keep_featured_image_loaded
     * @return int|null
     * @throws InitializingModelWithWrongPostType
     * @throws InvalidAcfFunction
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
