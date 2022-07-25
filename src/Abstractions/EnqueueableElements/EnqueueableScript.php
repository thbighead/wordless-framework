<?php

namespace Wordless\Abstractions\EnqueueableElements;

use Wordless\Abstractions\AbstractEnqueueableElement;
use Wordless\Exceptions\DuplicatedEnqueuableId;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\ProjectPath;

class EnqueueableScript extends AbstractEnqueueableElement
{
    /**
     * @return void
     * @throws PathNotFoundException
     */
    public function enqueue(): void
    {
        wp_enqueue_script($this->id, $this->filepath(), $this->dependencies, $this->version(), false);
    }

    /**
     * @return string
     * @throws PathNotFoundException
     */
    protected function filepath(): string
    {
        return ProjectPath::theme($this->relative_file_path);
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