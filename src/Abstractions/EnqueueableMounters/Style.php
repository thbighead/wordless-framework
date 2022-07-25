<?php

namespace Wordless\Abstractions\EnqueueableMounters;

use Wordless\Abstractions\AbstractEnqueueableMounter;
use Wordless\Abstractions\EnqueueableElements\EnqueueableStyle;
use Wordless\Exceptions\DuplicatedEnqueuableId;
use Wordless\Exceptions\InvalidMediaOption;

abstract class Style extends AbstractEnqueueableMounter
{
    protected function media(): string
    {
        return 'all';
    }

    /**
     * @return EnqueueableStyle
     * @throws DuplicatedEnqueuableId
     * @throws InvalidMediaOption
     */
    protected function mount(): EnqueueableStyle
    {
        return new EnqueueableStyle(
            $this->id(),
            $this->relativeFilePath(),
            $this->dependencies(),
            $this->version(),
            $this->media()
        );
    }
}