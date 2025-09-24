<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\EnqueueableAsset;

use Wordless\Application\Helpers\Link;
use Wordless\Application\Helpers\Link\Traits\Internal\Exceptions\FailedToGuessBaseAssetsUri;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset\EnqueueableStyle\Enums\MediaOption;

abstract class EnqueueableStyle extends EnqueueableAsset
{
    /**
     * @return string[]|EnqueueableStyle[]
     */
    protected function dependencies(): array
    {
        return parent::dependencies();
    }

    protected function media(): MediaOption
    {
        return MediaOption::all;
    }

    /**
     * @return string
     * @throws FailedToGuessBaseAssetsUri
     */
    protected function mountFileUrl(): string
    {
        return Link::css($this->filename());
    }

    final protected function callWpEnqueueFunction(): void
    {
        wp_enqueue_style(
            $this->getId(),
            $this->getFileUrl(),
            $this->getDependenciesIds(),
            $this->getVersion(),
            $this->getMedia()
        );
    }

    private function getMedia(): string
    {
        return $this->media()->name;
    }
}
