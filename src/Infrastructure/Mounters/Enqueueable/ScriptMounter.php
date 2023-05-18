<?php

namespace Wordless\Infrastructure\Mounters\Enqueueable;

use Wordless\Infrastructure\EnqueueableAsset\EnqueueableScript;
use Wordless\Infrastructure\EnqueueableAsset\Exceptions\DuplicatedEnqueueableId;

abstract class ScriptMounter extends EnqueueableMounter
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
