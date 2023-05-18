<?php

namespace Wordless\Infrastructure\EnqueueableAsset;

use Wordless\Exceptions\InvalidMediaOption;
use Wordless\Infrastructure\EnqueueableAsset;
use Wordless\Infrastructure\EnqueueableAsset\Exceptions\DuplicatedEnqueueableId;

class EnqueueableStyle extends EnqueueableAsset
{
    public static function configKey(): string
    {
        return 'styles';
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
     * @throws DuplicatedEnqueueableId
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

    public function enqueue(): void
    {
        wp_enqueue_style($this->id, $this->filepath(), $this->dependencies, $this->version(), $this->media());
    }

    protected function media(): string
    {
        return $this->media;
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
