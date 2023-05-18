<?php

namespace Wordless\Application\Mounters\Stub;

use Wordless\Infrastructure\Mounters\StubMounter;

class CommandStubMounter extends StubMounter
{
    protected function relativeStubFilename(): string
    {
        return 'command';
    }
}
