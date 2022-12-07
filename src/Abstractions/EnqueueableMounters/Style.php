<?php

namespace Wordless\Abstractions\EnqueueableMounters;

use Wordless\Abstractions\EnqueueableMounter;
use Wordless\Abstractions\EnqueueableElements\EnqueueableStyle;
use Wordless\Exceptions\DuplicatedEnqueueableId;
use Wordless\Exceptions\InvalidMediaOption;

abstract class Style extends EnqueueableMounter
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
