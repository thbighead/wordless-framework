<?php

namespace Wordless\Abstractions\EnqueueableElements;

use Wordless\Abstractions\EnqueueableElement;

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

        if (!empty($extra_data = $this->extraData())) {
            wp_localize_script($this->id, "wp_$this->id", $extra_data);
        }
    }

    protected function extraData(): array
    {
        return [];
    }
}
