<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\EnqueueableAsset;

use Wordless\Application\Helpers\Link;
use Wordless\Application\Helpers\Link\Traits\Internal\Exceptions\FailedToGuessBaseAssetsUri;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset;

abstract class EnqueueableScript extends EnqueueableAsset
{
    /**
     * @return string[]|EnqueueableScript[]
     */
    protected function dependencies(): array
    {
        return parent::dependencies();
    }

    /**
     * @return string
     * @throws FailedToGuessBaseAssetsUri
     */
    protected function mountFileUrl(): string
    {
        return Link::js($this->filename());
    }

    final protected function callWpEnqueueFunction(): void
    {
        wp_enqueue_script(
            $this->getId(),
            $this->getFileUrl(),
            $this->getDependenciesIds(),
            $this->getVersion(),
            false
        );
    }
}
