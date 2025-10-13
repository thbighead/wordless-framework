<?php declare(strict_types=1);

namespace Wordless\Application\Mounters\Stub;

use Wordless\Infrastructure\Mounters\StubMounter;

class CustomTaxonomyStubMounter extends StubMounter
{
    protected function relativeStubFilename(): string
    {
        return 'custom-taxonomy';
    }
}
