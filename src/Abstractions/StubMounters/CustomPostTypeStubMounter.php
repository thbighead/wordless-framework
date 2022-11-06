<?php

namespace Wordless\Abstractions\StubMounters;

class CustomPostTypeStubMounter extends BaseStubMounter
{
    protected function relativeStubFilename(): string
    {
        return 'custom-post-type';
    }
}
