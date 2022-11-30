<?php

namespace Wordless\Abstractions\EnqueueableElements;

use Wordless\Abstractions\AbstractEnqueueableElement;
use Wordless\Exceptions\DuplicatedEnqueueableId;

class EnqueueableScript extends AbstractEnqueueableElement
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

    /**
     * @param string $id
     * @return void
     * @throws DuplicatedEnqueueableId
     */
    protected function setId(string $id): void
    {
        parent::setId("script-$id");
    }
}
