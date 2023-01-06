<?php

namespace Wordless\Abstractions\StubMounters;

class CustomPostTypeStubMounter extends StubMounter
{
    protected function relativeStubFilename(): string
    {
        return 'custom-post-type';
    }
}
