<?php

namespace Wordless\Abstractions;

use Wordless\Hookers\RestApi\Authentication;
use Wordless\Hookers\RestApi\DefineEndpoints;

class RestApi
{
    public static function addAdditionalHooks(): array
    {
        return [
            Authentication::class,
            DefineEndpoints::class,
        ];
    }
}
