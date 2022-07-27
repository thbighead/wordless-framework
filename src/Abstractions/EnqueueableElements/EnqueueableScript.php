<?php

namespace Wordless\Abstractions\EnqueueableElements;

use Wordless\Abstractions\AbstractEnqueueableElement;
use Wordless\Exceptions\DuplicatedEnqueuableId;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\ProjectPath;

class EnqueueableScript extends AbstractEnqueueableElement
{
    public static function configKey(): string
    {
        return 'scripts';
    }

    /**
     * @return void
     * @throws PathNotFoundException
     */
    public function enqueue(): void
    {
        wp_enqueue_script($this->id, $this->filepath(), $this->dependencies, $this->version(), false);
    }

    /**
     * @param string $id
     * @return void
     * @throws DuplicatedEnqueuableId
     */
    protected function setId(string $id): void
    {
        parent::setId("script-$id");
    }
}