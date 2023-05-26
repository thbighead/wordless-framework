<?php

namespace Wordless\Infrastructure\Mounters\EnqueueableMounter;

use Wordless\Infrastructure\EnqueueableAsset\EnqueueableScript;
use Wordless\Infrastructure\EnqueueableAsset\Exceptions\DuplicatedEnqueueableId;
use Wordless\Infrastructure\Mounters\EnqueueableMounter;

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
