<?php

namespace Wordless\Application\Listeners\CustomLoginUrl;

class SiteUrlCustomLoginUrlHooker extends CustomLoginUrlHooker
{

    /**
     * WordPress action|filter number of arguments accepted by function
     */
    protected const ACCEPTED_NUMBER_OF_ARGUMENTS = 4;
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'load';
    /**
     * WordPress action|filter hook identification
     */
    protected const HOOK = 'site_url';

    /**
     * action or filter type (defines which method will be called: add_action or add_filter)
     */
    protected const TYPE = 'filter';

    public static function load($url, $path, $scheme, $blog_id)
    {
        if (self::canHook()) {
            return self::filterWpLoginPhp($url, $scheme);
        }

        return self::defaultSiteUrl($blog_id, $url, $scheme, $path);
    }

    /**
     * @param $blog_id
     * @param $url
     * @param $scheme
     * @param $path
     * @return string
     */
    public static function defaultSiteUrl($blog_id, $url, $scheme, $path): string
    {
        if (empty($blog_id) || !is_multisite()) {
            $url = get_option('siteurl');
        } else {
            switch_to_blog($blog_id);
            $url = get_option('siteurl');
            restore_current_blog();
        }

        $url = set_url_scheme($url, $scheme);

        if ($path && is_string($path)) {
            $url .= '/' . ltrim($path, '/');
        }

        return $url;
    }
}
