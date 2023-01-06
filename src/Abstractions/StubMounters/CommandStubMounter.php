<?php

namespace Wordless\Abstractions\StubMounters;

class CommandStubMounter extends StubMounter
{
    protected function relativeStubFilename(): string
    {
        return 'command';
    }
}
