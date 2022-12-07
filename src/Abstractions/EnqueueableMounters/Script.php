<?php

namespace Wordless\Abstractions\EnqueueableMounters;

use Wordless\Abstractions\EnqueueableMounter;
use Wordless\Abstractions\EnqueueableElements\EnqueueableScript;
use Wordless\Exceptions\DuplicatedEnqueueableId;

abstract class Script extends EnqueueableMounter
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
