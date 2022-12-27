<?php

namespace Wordless\Abstractions\StubMounters;

class ControllerStubMounter extends StubMounter
{
    protected function relativeStubFilename(): string
    {
        return 'controller';
    }
}
