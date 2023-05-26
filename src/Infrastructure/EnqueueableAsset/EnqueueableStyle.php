<?php

namespace Wordless\Infrastructure\EnqueueableAsset;

use Wordless\Infrastructure\EnqueueableAsset;
use Wordless\Infrastructure\EnqueueableAsset\EnqueueableStyle\Enums\MediaOption;
use Wordless\Infrastructure\EnqueueableAsset\Exceptions\DuplicatedEnqueueableId;

class EnqueueableStyle extends EnqueueableAsset
{
    public static function configKey(): string
    {
        return 'styles';
    }

    /**
     * @param string $id
     * @param string $relative_file_path
     * @param array $dependencies
     * @param string|null $version
     * @param MediaOption $media
     * @throws DuplicatedEnqueueableId
     */
    public function __construct(
        string                       $id,
        string                       $relative_file_path,
        array                        $dependencies = [],
        ?string                      $version = null,
        private readonly MediaOption $media = MediaOption::ALL
    )
    {
        parent::__construct($id, $relative_file_path, $dependencies, $version);
    }

    public function enqueue(): void
    {
        wp_enqueue_style($this->id, $this->filepath(), $this->dependencies, $this->version(), $this->media());
    }

    protected function media(): string
    {
        return $this->media->value;
    }
}
