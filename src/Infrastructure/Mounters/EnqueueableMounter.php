<?php

namespace Wordless\Infrastructure\Mounters;

use Wordless\Infrastructure\Wordpress\EnqueueableAsset;

abstract class EnqueueableMounter
{
    abstract public static function id(): string;

    abstract protected function mount(): EnqueueableAsset;

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
