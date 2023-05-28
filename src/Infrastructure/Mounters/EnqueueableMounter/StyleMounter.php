<?php

namespace Wordless\Infrastructure\Mounters\EnqueueableMounter;

use Wordless\Exceptions\InvalidMediaOption;
use Wordless\Infrastructure\Mounters\EnqueueableMounter;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset\EnqueueableStyle;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset\Exceptions\DuplicatedEnqueueableId;

abstract class StyleMounter extends EnqueueableMounter
{
    protected function media(): string
    {
        return 'all';
    }

    /**
     * @return EnqueueableStyle
     * @throws DuplicatedEnqueueableId
     * @throws InvalidMediaOption
     */
    protected function mount(): EnqueueableStyle
    {
        return new EnqueueableStyle(
            static::id(),
            $this->relativeFilePath(),
            $this->dependencies(),
            $this->version(),
            $this->media()
        );
    }
}
