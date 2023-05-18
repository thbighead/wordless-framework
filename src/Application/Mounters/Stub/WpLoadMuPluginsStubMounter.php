<?php

namespace Wordless\Application\Mounters\Stub;

use Wordless\Infrastructure\Mounters\StubMounter;

class WpLoadMuPluginsStubMounter extends StubMounter
{
    protected function relativeStubFilename(): string
    {
        return 'wp-load-mu-plugins.php';
    }
}
