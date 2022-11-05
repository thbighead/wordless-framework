<?php

namespace Wordless\Abstractions\StubMounters;

class CommandStubMounter extends BaseStubMounter
{
    protected function relativeStubFilename(): string
    {
        return 'command.php';
    }
}
