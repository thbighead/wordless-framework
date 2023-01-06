<?php

namespace Wordless\Abstractions\StubMounters;

class ExceptionStubMounter extends StubMounter
{
    protected function relativeStubFilename(): string
    {
        return 'exception';
    }
}
