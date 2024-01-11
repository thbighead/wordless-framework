<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\EnqueueableAsset;

use Wordless\Infrastructure\Wordpress\EnqueueableAsset;

abstract class EnqueueableScript extends EnqueueableAsset
{
    /**
     * @return string[]|EnqueueableScript[]
     */
    protected static function dependencies(): array
    {
        return parent::dependencies();
    }

    public function enqueue(): void
    {
        wp_enqueue_script(
            $this->getId(),
            $this->getFilepath(),
            $this->getDependencies(),
            $this->getVersion(),
            false
        );
    }
}
