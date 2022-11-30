<?php

namespace Wordless\Abstractions\EnqueueableMounters;

use Wordless\Abstractions\AbstractEnqueueableMounter;
use Wordless\Abstractions\EnqueueableElements\EnqueueableStyle;
use Wordless\Exceptions\DuplicatedEnqueueableId;
use Wordless\Exceptions\InvalidMediaOption;

abstract class Style extends AbstractEnqueueableMounter
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
