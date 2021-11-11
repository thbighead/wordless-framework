<?php

namespace Wordless\Abstractions;

use Wordless\Bootables\BootControllers;

class Bootstrapper
{
    public static function bootAll()
    {
        BootControllers::boot();
    }
}