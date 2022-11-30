<?php

namespace Wordless\Abstractions\StubMounters;

class MigrationStubMounter extends BaseStubMounter
{
    protected function relativeStubFilename(): string
    {
        return 'migration';
    }
}
