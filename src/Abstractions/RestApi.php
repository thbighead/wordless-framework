<?php

namespace Wordless\Abstractions;

use Wordless\Hookers\RestApiAuthentication;
use Wordless\Hookers\SyncRestApiConfigEndpoints;

class RestApi
{
    public static function addAdditionalHooks(): array
    {
        return [
            RestApiAuthentication::class,
            SyncRestApiConfigEndpoints::class,
        ];
    }
}
