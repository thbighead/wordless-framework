<?php

namespace Wordless\Application\Listeners\CustomLoginUrl;

class NetworkSiteUrlCustomLoginUrlHooker extends CustomLoginUrlHooker
{

    /**
     * WordPress action|filter number of arguments accepted by function
     */
    protected const ACCEPTED_NUMBER_OF_ARGUMENTS = 3;
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'load';
    /**
     * WordPress action|filter hook identification
     */
    protected const HOOK = 'network_site_url';

    /**
     * action or filter type (defines which method will be called: add_action or add_filter)
     */
    protected const TYPE = 'filter';

    public static function load($url, $path, $scheme)
    {
        if (self::canHook()) {
            return self::filterWpLoginPhp($url, $scheme);
        }

        return self::defaultNetworkSiteUrl($path, $scheme, $url);
    }

    /**
     * @param $path
     * @param $scheme
     * @param string $url
     * @return string|null
     */
    public static function defaultNetworkSiteUrl($path, $scheme, string $url): ?string
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
