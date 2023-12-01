<?php

namespace Wordless\Application\Listeners\CustomLoginUrl;

use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Listeners\CustomLoginUrl\Traits\Common;
use Wordless\Infrastructure\Wordpress\Listener\FilterListener;
use Wordless\Wordpress\Hook\Contracts\FilterHook;
use Wordless\Wordpress\Hook\Enums\Filter;

class NetworkSiteUrlCustomLoginUrl extends FilterListener
{
    use Common;

    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'load';

    /**
     * @throws PathNotFoundException
     */
    public static function load($url, $path, $scheme)
    {
        if (static::canHook()) {
            return static::filterWpLoginPhp($url, $scheme);
        }

        return static::defaultNetworkSiteUrl($path, $scheme, $url);
    }

    protected static function functionNumberOfArgumentsAccepted(): int
    {
        return 3;
    }

    protected static function hook(): FilterHook
    {
        return Filter::network_site_url;
    }

    /**
     * @param $path
     * @param $scheme
     * @param string $url
     * @return string|null
     */
    private static function defaultNetworkSiteUrl($path, $scheme, string $url): ?string
    {
        if (!is_multisite()) {
            return site_url($path, $scheme);
        }

        $current_network = get_network();

        if ('relative' === $scheme) {
            $url = $current_network->path;
        } else {
            $url = set_url_scheme('http://' . $current_network->domain . $current_network->path, $scheme);
        }

        if ($path && is_string($path)) {
            $url .= ltrim($path, '/');
        }
        return $url;
    }
}
