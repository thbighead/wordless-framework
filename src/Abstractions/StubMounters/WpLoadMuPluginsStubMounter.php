<?php

namespace Wordless\Abstractions\StubMounters;

class WpLoadMuPluginsStubMounter extends StubMounter
{
    protected function relativeStubFilename(): string
    {
        return 'wp-load-mu-plugins.php';
    }
}
