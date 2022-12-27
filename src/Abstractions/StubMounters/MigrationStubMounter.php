<?php

namespace Wordless\Abstractions\StubMounters;

class MigrationStubMounter extends StubMounter
{
    protected function relativeStubFilename(): string
    {
        return 'migration';
    }
}
