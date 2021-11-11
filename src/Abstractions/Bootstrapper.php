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
        foreach (include ProjectPath::config('bootable.php') as $bootable_class_namespace) {
            /** @var AbstractBootable $bootable_class_namespace */
            $bootable_class_namespace::boot();
        }
    }
}