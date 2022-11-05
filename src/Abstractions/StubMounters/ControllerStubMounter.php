<?php

namespace Wordless\Abstractions\StubMounters;

class ControllerStubMounter extends BaseStubMounter
{
    protected function relativeStubFilename(): string
    {
        return 'controller.php';
    }
}
