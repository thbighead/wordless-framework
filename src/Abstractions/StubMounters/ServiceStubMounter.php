<?php

namespace Wordless\Abstractions\StubMounters;

class ServiceStubMounter extends BaseStubMounter
{
    protected function relativeStubFilename(): string
    {
        return 'service';
    }
}
