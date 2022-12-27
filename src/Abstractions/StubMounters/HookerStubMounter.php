<?php

namespace Wordless\Abstractions\StubMounters;

class HookerStubMounter extends StubMounter
{
    protected function relativeStubFilename(): string
    {
        return 'hooker';
    }
}
