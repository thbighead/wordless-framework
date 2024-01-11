<?php declare(strict_types=1);

namespace Wordless\Application\Mounters\Stub;

use Wordless\Infrastructure\Mounters\StubMounter;

class CustomPostTypeStubMounter extends StubMounter
{
    protected function relativeStubFilename(): string
    {
        return 'custom-post-type';
    }
}
