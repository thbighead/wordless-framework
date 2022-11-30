<?php

namespace Wordless\Abstractions\StubMounters;

class ExceptionStubMounter extends BaseStubMounter
{
    protected function relativeStubFilename(): string
    {
        return 'exception';
    }
}
