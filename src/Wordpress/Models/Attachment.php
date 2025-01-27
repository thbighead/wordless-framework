<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models;

use Symfony\Component\Dotenv\Exception\FormatException;
use Wordless\Application\Helpers\Arr;
use Wordless\Application\Helpers\Arr\Exceptions\FailedToParseArrayKey;
use Wordless\Application\Helpers\Str;
use Wordless\Core\Exceptions\DotEnvNotSetException;
use Wordless\Wordpress\Models\Attachment\DTO\MediaDTO;
use Wordless\Wordpress\Models\Attachment\DTO\MediaDTO\DTO\SizeDTO;
use Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Traits\WithMetaData\Traits\Crud\Traits\Read\Exceptions\InvalidMetaKey;
use Wordless\Wordpress\Models\Post\Exceptions\FailedToGetPermalink;
use Wordless\Wordpress\Models\Post\Exceptions\InitializingModelWithWrongPostType;
use Wordless\Wordpress\Models\PostType\Enums\StandardType;
use Wordless\Wordpress\Models\PostType\Exceptions\PostTypeNotRegistered;
use Wordless\Wordpress\Models\Traits\WithAcfs\Exceptions\InvalidAcfFunction;
use WP_Post;

class Attachment extends Post
{
    final public const KEY_ALTERNATIVE_TEXT = 'alt';
    final public const KEY_MIME_TYPE = 'mime-type';
    protected const TYPE_KEY = StandardType::attachment->name;

    readonly public string $alt;
    readonly public string $caption;
    readonly public string $description;
    readonly public MediaDTO $media;
    readonly protected array $raw_metadata;

    /**
     * @param int|WP_Post $post
     * @param bool $with_acfs
     * @throws DotEnvNotSetException
     * @throws FailedToParseArrayKey
     * @throws FormatException
     * @throws InitializingModelWithWrongPostType
     * @throws InvalidAcfFunction
     * @throws InvalidMetaKey
     * @throws PostTypeNotRegistered
     */
    public function __construct(int|WP_Post $post, bool $with_acfs = true)
    {
        parent::__construct($post, $with_acfs);

        $this->setRawMetadata()->setAltText()->setMedia();

        $caption = trim($this->post_excerpt);
        $description = trim($this->post_content);

        $this->caption = empty($caption) ? $this->post_title : $caption;
        $this->description = empty($description) ? $this->post_title : $description;
    }

    /**
     * @param string $extra_tag_attributes
     * @param bool $downloadable
     * @return string
     * @throws FailedToGetPermalink
     */
    public function html(string $extra_tag_attributes = '', bool $downloadable = false): string
    {
        $extra_tag_attributes = empty($extra_tag_attributes)
            ? ''
            : Str::startWith($extra_tag_attributes, ' ');

        if ($downloadable) {
            return "<a href='{$this->url()}' title='$this->caption' download='$this->post_title'$extra_tag_attributes>$this->description</a>";
        }

        if (!Str::beginsWith($this->post_mime_type, 'image/')) {
            return "<a href='{$this->url()}' title='$this->caption' target='_blank'$extra_tag_attributes>$this->description</a>";
        }

        if (!$this->media->hasSizes()) {
            return "<img src='{$this->url()}' alt='$this->alt' title='$this->caption'$extra_tag_attributes />";
        }

        $picture = "<picture$extra_tag_attributes>";
        $sizes = $this->media->sizes;
        $originalSize = $sizes[SizeDTO::TYPE_ORIGINAL];

        unset($sizes[SizeDTO::TYPE_ORIGINAL]);

        foreach ($sizes as $size) {
            $picture .= "<source srcset='$size->url' media='(max-width:{$size->width}px)' />";
        }

        return "$picture<img src='$originalSize->url' alt='$this->alt' /></picture>";
    }

    private function setAltText(): static
    {
        $this->alt = trim($this->raw_metadata[self::KEY_ALTERNATIVE_TEXT] ?? '');

        return $this;
    }

    /**
     * @return void
     * @throws DotEnvNotSetException
     * @throws FormatException
     */
    private function setMedia(): void
    {
        $this->media = new MediaDTO($this->raw_metadata + [self::KEY_MIME_TYPE => $this->post_mime_type]);
    }

    /**
     * @return $this
     * @throws FailedToParseArrayKey
     * @throws InvalidMetaKey
     */
    private function setRawMetadata(): static
    {
        $raw_metadata_string = Arr::unwrap($this->getMetaField('_wp_attachment_metadata', []));
        $raw_metadata = is_string($raw_metadata_string) ? unserialize($raw_metadata_string) : [];

        if (!empty($raw_metadata)) {
            $raw_metadata[SizeDTO::KEY_FILE] ??= Arr::unwrap($this->getMetaField('_wp_attached_file', []));
            $raw_metadata[self::KEY_ALTERNATIVE_TEXT] = Arr::unwrap($this->getMetaField(
                '_wp_attachment_image_alt',
                ['']
            ));
        }

        $this->raw_metadata = $raw_metadata;

        return $this;
    }
}
