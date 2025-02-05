<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models;

use InvalidArgumentException;
use Symfony\Component\Dotenv\Exception\FormatException;
use Symfony\Component\Dotenv\Exception\PathException;
use Wordless\Application\Helpers\Database;
use Wordless\Application\Helpers\Database\Exceptions\QueryError;
use Wordless\Application\Helpers\DirectoryFiles;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToCopyFile;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Core\Exceptions\DotEnvNotSetException;
use Wordless\Wordpress\Models\Attachment\DTO\MediaDTO;
use Wordless\Wordpress\Models\Attachment\DTO\MediaDTO\DTO\SizeDTO;
use Wordless\Wordpress\Models\Attachment\Exceptions\FailedToCreateAttachmentFromFile;
use Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Traits\WithMetaData\Traits\Crud\Traits\Read\Exceptions\InvalidMetaKey;
use Wordless\Wordpress\Models\Post\Enums\StandardStatus;
use Wordless\Wordpress\Models\Post\Exceptions\FailedToGetPermalink;
use Wordless\Wordpress\Models\Post\Exceptions\InitializingModelWithWrongPostType;
use Wordless\Wordpress\Models\PostType\Enums\StandardType;
use Wordless\Wordpress\Models\PostType\Exceptions\PostTypeNotRegistered;
use Wordless\Wordpress\Models\Traits\WithAcfs\Exceptions\InvalidAcfFunction;
use Wordless\Wordpress\Models\Traits\WithAcfs\Traits\Validate;
use WP_Error;
use WP_Post;

class Attachment extends Post
{
    use Validate;

    final public const KEY_ALTERNATIVE_TEXT = 'alt';
    final public const KEY_MIME_TYPE = 'mime-type';
    protected const TYPE_KEY = StandardType::attachment->name;

    readonly public string $alt;
    readonly public string $caption;
    readonly public string $description;
    readonly public MediaDTO $media;
    readonly protected array $raw_metadata;

    /**
     * @param string $absolute_filepath
     * @param bool $secure_mode
     * @return int
     * @throws FailedToCopyFile
     * @throws FailedToCreateAttachmentFromFile
     * @throws InvalidArgumentException
     * @throws PathNotFoundException
     * @throws QueryError
     * @noinspection PhpDocRedundantThrowsInspection
     */
    public static function createFromFile(string $absolute_filepath, bool $secure_mode = true): int
    {
        /**
         * @return int
         * @throws FailedToCopyFile
         * @throws FailedToCreateAttachmentFromFile
         * @throws InvalidArgumentException
         * @throws PathNotFoundException
         */
        $transaction = function () use ($absolute_filepath, $secure_mode): int {
            $result = wp_insert_attachment([
                'post_mime_type' => mime_content_type($absolute_filepath),
                'post_title' => (string)Str::of(basename($absolute_filepath))
                    ->before('.')
                    ->replace(['-', '_'], ' ')
                    ->titleCase(),
                'post_status' => StandardStatus::inherit->value,
            ], $wp_uploads_new_filepath = DirectoryFiles::copyFileToWpUploads(
                $absolute_filepath,
                secure_mode: $secure_mode
            ));

            if ($result instanceof WP_Error || $result === 0) {
                throw new FailedToCreateAttachmentFromFile(
                    $result,
                    $absolute_filepath,
                    $wp_uploads_new_filepath,
                    $secure_mode
                );
            }

            if (wp_update_attachment_metadata(
                    abs($result),
                    wp_generate_attachment_metadata($result, $wp_uploads_new_filepath)
                ) === false) {
                throw new FailedToCreateAttachmentFromFile(
                    $result,
                    $absolute_filepath,
                    $wp_uploads_new_filepath,
                    $secure_mode
                );
            }

            return $result;
        };

        return Database::smartTransaction($transaction);
    }

    /**
     * @param int|WP_Post $post
     * @param bool $with_acfs
     * @throws DotEnvNotSetException
     * @throws FormatException
     * @throws InitializingModelWithWrongPostType
     * @throws InvalidAcfFunction
     * @throws InvalidMetaKey
     * @throws PathException
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
     * @return array<string, mixed>
     * @throws InvalidAcfFunction
     */
    public function fileAsAcfArray(): array
    {
        return $this->validateAcfFunction('acf_get_attachment')($this->asWpPost());
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

    /**
     * @param string $absolute_filepath
     * @param bool $secure_mode
     * @return string
     * @throws FailedToCopyFile
     * @throws FailedToCreateAttachmentFromFile
     * @throws InvalidArgumentException
     * @throws PathNotFoundException
     * @throws QueryError
     * @noinspection PhpDocRedundantThrowsInspection
     */
    public function updateFile(string $absolute_filepath, bool $secure_mode = true): string
    {
        /**
         * @return string
         * @throws FailedToCopyFile
         * @throws FailedToCreateAttachmentFromFile
         * @throws InvalidArgumentException
         * @throws PathNotFoundException
         */
        $transaction = function () use ($absolute_filepath, $secure_mode): string {
            $result = wp_insert_attachment([
                'ID' => $this->id(),
                'post_mime_type' => mime_content_type($absolute_filepath),
                'post_title' => (string)Str::of(basename($absolute_filepath))
                    ->before('.')
                    ->replace(['-', '_'], ' ')
                    ->titleCase(),
            ], $wp_uploads_new_filepath = DirectoryFiles::copyFileToWpUploads(
                $absolute_filepath,
                secure_mode: $secure_mode
            ));

            if ($result instanceof WP_Error || $result === 0) {
                throw new FailedToCreateAttachmentFromFile(
                    $result,
                    $absolute_filepath,
                    $wp_uploads_new_filepath,
                    $secure_mode
                );
            }

            if (wp_update_attachment_metadata(
                    abs($result),
                    wp_generate_attachment_metadata($result, $wp_uploads_new_filepath)
                ) === false) {
                throw new FailedToCreateAttachmentFromFile(
                    $result,
                    $absolute_filepath,
                    $wp_uploads_new_filepath,
                    $secure_mode
                );
            }

            return $wp_uploads_new_filepath;
        };

        return Database::smartTransaction($transaction);
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
     * @throws PathException
     */
    private function setMedia(): void
    {
        $this->media = new MediaDTO($this->raw_metadata + [self::KEY_MIME_TYPE => $this->post_mime_type]);
    }

    /**
     * @return $this
     * @throws InvalidMetaKey
     */
    private function setRawMetadata(): static
    {
        $raw_metadata = $this->getMetaField('_wp_attachment_metadata', []);

        if (!empty($raw_metadata)) {
            $raw_metadata[SizeDTO::KEY_FILE] ??= $this->getMetaField('_wp_attached_file', []);
            $raw_metadata[self::KEY_ALTERNATIVE_TEXT] = $this->getMetaField(
                '_wp_attachment_image_alt',
                ['']
            );
        }

        $this->raw_metadata = $raw_metadata;

        return $this;
    }
}
