<?php

namespace Wordless\Application\Providers;

use Wordless\Application\Listeners\RestApi\Authentication;
use Wordless\Application\Listeners\RestApi\DefineEndpoints;

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
