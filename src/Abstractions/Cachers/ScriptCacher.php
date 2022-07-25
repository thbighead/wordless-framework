<?php

namespace Wordless\Abstractions\Cachers;

use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\ProjectPath;

class ScriptCacher extends EnqueueableElementCacher
{
    /**
     * @return string
     * @throws PathNotFoundException
     */
    protected static function mounterDirectoryPath(): string
    {
        return ProjectPath::scripts();
    }

    protected function cacheFilename(): string
    {
        return 'scripts.php';
    }
}
