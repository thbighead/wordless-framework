<?php

namespace Wordless\Infrastructure\Provider\Traits;

use Wordless\Infrastructure\Wordpress\EnqueueableAsset\EnqueueableScript;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset\EnqueueableStyle;

trait EnqueueablesRegistration
{
    /**
     * @return string[]|EnqueueableScript[]
     */
    public function registerEnqueueableScripts(): array
    {
        return [];
    }

    /**
     * @return string[]|EnqueueableStyle[]
     */
    public function registerEnqueueableStyles(): array
    {
        return [];
    }
}
