<?php

namespace Wordless\Abstractions\EnqueueableElements;

use Wordless\Abstractions\EnqueueableElement;
use Wordless\Exceptions\DuplicatedEnqueueableId;

class EnqueueableScript extends EnqueueableElement
{
    public static function configKey(): string
    {
        return 'scripts';
    }

    /**
     * @return void
     */
    public function enqueue(): void
    {
        /** @noinspection PhpRedundantOptionalArgumentInspection */
        wp_enqueue_script($this->id, $this->filepath(), $this->dependencies, $this->version(), false);
    }
}
