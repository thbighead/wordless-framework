<?php

namespace Wordless\Abstractions\StubMounters;

class HookerStubMounter extends BaseStubMounter
{
    protected function relativeStubFilename(): string
    {
        return 'hooker';
    }
}
