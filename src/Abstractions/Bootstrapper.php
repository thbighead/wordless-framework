<?php

namespace Wordless\Abstractions;

use Wordless\Exception\PathNotFoundException;
use Wordless\Helpers\ProjectPath;

class Bootstrapper
{
    /**
     * @throws PathNotFoundException
     */
    public static function bootAll()
    {
        foreach (include ProjectPath::config('hookers.php') as $bootable_class_namespace) {
            /** @var AbstractHooker $bootable_class_namespace */
            $bootable_class_namespace::boot();
        }
    }
}