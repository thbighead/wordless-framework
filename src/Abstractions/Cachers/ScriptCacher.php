<?php

namespace Wordless\Abstractions\Cachers;

use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\ProjectPath;

class ScriptCacher extends EnqueueableElementCacher
{
    protected function cacheFilename(): string
    {
        return 'scripts.php';
    }

    /**
     * @return string
     * @throws PathNotFoundException
     */
    protected function mounterDirectoryPath(): string
    {
        return ProjectPath::scripts();
    }
}
