<?php

namespace Wordless\Application\Mounters\Stub;

use Wordless\Infrastructure\Mounters\StubMounter;

class WpConfigStubMounter extends StubMounter
{
    protected function relativeStubFilename(): string
    {
        return 'wp-config.php';
    }
}
