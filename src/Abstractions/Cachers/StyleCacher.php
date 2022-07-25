<?php

namespace Wordless\Abstractions\Cachers;

use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\ProjectPath;

class StyleCacher extends EnqueueableElementCacher
{
    /**
     * @return string
     * @throws PathNotFoundException
     */
    protected static function mounterDirectoryPath(): string
    {
        return ProjectPath::styles();
    }

    protected function cacheFilename(): string
    {
        return 'styles.php';
    }
}
