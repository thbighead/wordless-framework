<?php

namespace Wordless\Abstractions\EnqueueableElements;

use Wordless\Abstractions\AbstractEnqueueableElement;
use Wordless\Abstractions\AbstractEnqueueableMounter;
use Wordless\Abstractions\Cachers\StyleCacher;
use Wordless\Abstractions\InternalCache;
use Wordless\Exceptions\DuplicatedEnqueuableId;
use Wordless\Exceptions\FailedToFindCachedKey;
use Wordless\Exceptions\InternalCacheNotLoaded;
use Wordless\Exceptions\InvalidMediaOption;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\ProjectPath;

class EnqueueableStyle extends AbstractEnqueueableElement
{
    /**
     * @return void
     * @throws InternalCacheNotLoaded
     * @throws PathNotFoundException
     */
    public static function enqueueAll(): void
    {
        try {
            $style_mounters_to_queue = InternalCache::getValueOrFail(
                'styles.' . StyleCacher::CLASSES_KEY
            );
        } catch (FailedToFindCachedKey $exception) {
            $style_mounters_to_queue = StyleCacher::listEnqueueableElementsClasses()[StyleCacher::CLASSES_KEY] ?? [];
        }

        foreach ($style_mounters_to_queue as $style_mounter_class) {
            /** @var AbstractEnqueueableMounter $enqueueableStyleMounter */
            $enqueueableStyleMounter = new $style_mounter_class;
            $enqueueableStyleMounter->mountAndEnqueue();
        }
    }

    public const MEDIA_OPTION_ALL = 'all';
    public const MEDIA_OPTION_PRINT = 'print';
    public const MEDIA_OPTION_SCREEN = 'screen';
    public const MEDIA_OPTIONS = [
        self::MEDIA_OPTION_ALL => self::MEDIA_OPTION_ALL,
        self::MEDIA_OPTION_PRINT => self::MEDIA_OPTION_PRINT,
        self::MEDIA_OPTION_SCREEN => self::MEDIA_OPTION_SCREEN,
    ];

    private string $media;

    /**
     * @param string $id
     * @param string $relative_file_path
     * @param array $dependencies
     * @param string|null $version
     * @param string $media
     * @throws DuplicatedEnqueuableId
     * @throws InvalidMediaOption
     */
    public function __construct(
        string  $id,
        string  $relative_file_path,
        array   $dependencies = [],
        ?string $version = null,
        string  $media = self::MEDIA_OPTION_ALL
    )
    {
        parent::__construct($id, $relative_file_path, $dependencies, $version);
        $this->setMedia($media);
    }

    /**
     * @return void
     * @throws PathNotFoundException
     */
    public function enqueue(): void
    {
        wp_enqueue_style($this->id, $this->filepath(), $this->dependencies, $this->version(), $this->media());
    }

    /**
     * @return string
     * @throws PathNotFoundException
     */
    protected function filepath(): string
    {
        return ProjectPath::theme($this->relative_file_path);
    }

    protected function media(): string
    {
        return $this->media;
    }

    /**
     * @param string $id
     * @return void
     * @throws DuplicatedEnqueuableId
     */
    protected function setId(string $id): void
    {
        parent::setId("style-$id");
    }

    /**
     * @param string $media
     * @return void
     * @throws InvalidMediaOption
     */
    protected function setMedia(string $media): void
    {
        if ((self::MEDIA_OPTIONS[$media] ?? false) !== $media) {
            throw new InvalidMediaOption(static::class, $media);
        }

        $this->media = $media;
    }
}