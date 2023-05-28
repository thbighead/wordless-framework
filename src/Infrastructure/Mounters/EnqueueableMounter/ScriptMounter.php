<?php

namespace Wordless\Infrastructure\Mounters\EnqueueableMounter;

use Wordless\Infrastructure\Mounters\EnqueueableMounter;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset\EnqueueableScript;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset\Exceptions\DuplicatedEnqueueableId;

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
