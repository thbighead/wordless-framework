<?php

namespace Wordless\Application\Providers;

use Wordless\Application\Listeners\RestApi\Authentication;
use Wordless\Application\Listeners\RestApi\DefineEndpoints;
use Wordless\Infrastructure\Provider;
use Wordless\Infrastructure\Wordpress\Listener;

class RestApiProvider extends Provider
{

    /**
     * @return array|string[]|Listener[]
     */
    public function registerListeners(): array
    {
        return [
            Authentication::class,
            DefineEndpoints::class,
        ];
    }
}
