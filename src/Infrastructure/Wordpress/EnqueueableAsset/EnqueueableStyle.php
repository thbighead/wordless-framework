<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\EnqueueableAsset;

use Wordless\Infrastructure\Wordpress\EnqueueableAsset;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset\EnqueueableStyle\Enums\MediaOption;

abstract class EnqueueableStyle extends EnqueueableAsset
{
    /**
     * @return string[]|EnqueueableStyle[]
     */
    protected static function dependencies(): array
    {
        return parent::dependencies();
    }

    protected static function media(): MediaOption
    {
        return MediaOption::all;
    }

    public function enqueue(): void
    {
        wp_enqueue_style(
            $this->getId(),
            $this->getFilepath(),
            $this->getDependencies(),
            $this->getVersion(),
            $this->getMedia()
        );
    }

    private function getMedia(): string
    {
        return static::media()->name;
    }
}
