<?php

namespace Wordless\Abstractions\Cachers;

use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\ProjectPath;

class StyleCacher extends EnqueueableElementCacher
{
    protected function cacheFilename(): string
    {
        return 'styles.php';
    }

    /**
     * @return string
     * @throws PathNotFoundException
     */
    protected function mounterDirectoryPath(): string
    {
        return ProjectPath::styles();
    }
}
