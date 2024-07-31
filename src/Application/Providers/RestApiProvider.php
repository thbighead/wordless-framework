<?php declare(strict_types=1);

namespace Wordless\Application\Providers;

use Wordless\Application\Cachers\ApiControllerCacher;
use Wordless\Application\Listeners\RestApi\Authentication;
use Wordless\Application\Listeners\RestApi\DefineEndpoints;
use Wordless\Infrastructure\Cacher;
use Wordless\Infrastructure\Provider;
use Wordless\Infrastructure\Wordpress\Listener;

class RestApiProvider extends Provider
{
    /**
     * @return string[]|Cacher[]
     */
    public function registerInternalCachers(): array
    {
        return [
            ApiControllerCacher::class,
        ];
    }

    final public const CONFIG_KEY = 'rest-api';
    final public const CONFIG_KEY_ROUTES = 'routes';
    final public const CONFIG_ROUTES_KEY_ALLOW = 'allow';
    final public const CONFIG_ROUTES_KEY_DISALLOW = 'disallow';
    final public const CONFIG_ROUTES_KEY_PUBLIC = 'public';

    /**
     * @return string[]|Listener[]
     */
    public function registerListeners(): array
    {
        return [
            Authentication::class,
            DefineEndpoints::class,
        ];
    }
}
