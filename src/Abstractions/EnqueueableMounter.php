<?php

namespace Wordless\Abstractions;

abstract class EnqueueableMounter
{
    abstract public static function id(): string;

    abstract protected function mount(): EnqueueableElement;

    abstract protected function relativeFilePath(): string;

    /**
     * @return void
     */
    public function mountAndEnqueue()
    {
        $this->mount()->enqueue();
    }

    /**
     * @return array
     */
    protected function dependencies(): array
    {
        return [];
    }

    protected function version(): ?string
    {
        return null;
    }
}
