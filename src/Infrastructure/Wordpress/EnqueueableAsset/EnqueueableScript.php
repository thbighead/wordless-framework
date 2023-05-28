<?php

namespace Wordless\Infrastructure\Wordpress\EnqueueableAsset;

use Wordless\Infrastructure\Wordpress\EnqueueableAsset;

class EnqueueableScript extends EnqueueableAsset
{
    public static function configKey(): string
    {
        return 'scripts';
    }

    public function enqueue(): void
    {
        /** @noinspection PhpRedundantOptionalArgumentInspection */
        wp_enqueue_script($this->id, $this->filepath(), $this->dependencies, $this->version(), false);
    }
}
