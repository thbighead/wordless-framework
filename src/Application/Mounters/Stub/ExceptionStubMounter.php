<?php

namespace Wordless\Application\Mounters\Stub;

use Wordless\Infrastructure\Mounters\StubMounter;

class ExceptionStubMounter extends StubMounter
{
    protected function relativeStubFilename(): string
    {
        return 'exception';
    }
}
