<?php

namespace Wordless\Abstractions\EnqueueableMounters;

use Wordless\Abstractions\AbstractEnqueueableMounter;
use Wordless\Abstractions\EnqueueableElements\EnqueueableScript;
use Wordless\Exceptions\DuplicatedEnqueueableId;

abstract class Script extends AbstractEnqueueableMounter
{
    /**
     * @return EnqueueableScript
     * @throws DuplicatedEnqueueableId
     */
    protected function mount(): EnqueueableScript
    {
        return new EnqueueableScript(
            static::id(),
            $this->relativeFilePath(),
            $this->dependencies(),
            $this->version()
        );
    }
}
