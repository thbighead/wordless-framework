<?php

namespace Wordless\Abstractions\StubMounters;

class ServiceStubMounter extends StubMounter
{
    protected function relativeStubFilename(): string
    {
        return 'service';
    }
}
