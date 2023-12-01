<?php

namespace Wordless\Application\Listeners\CustomLoginUrl;

use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Listeners\CustomLoginUrl\Traits\Common;
use Wordless\Infrastructure\Wordpress\Listener\FilterListener;
use Wordless\Wordpress\Hook\Contracts\FilterHook;
use Wordless\Wordpress\Hook\Enums\Filter;

class SiteUrlCustomLoginUrl extends FilterListener
{
    use Common;

    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'load';

    /**
     * @throws PathNotFoundException
     */
    public static function load($url, $path, $scheme, $blog_id): string
    {
        if (static::canHook()) {
            return static::filterWpLoginPhp($url, $scheme);
        }

        return static::defaultSiteUrl($blog_id, $url, $scheme, $path);
    }

    protected static function functionNumberOfArgumentsAccepted(): int
    {
        return 4;
    }

    protected static function hook(): FilterHook
    {
        return Filter::site_url;
    }

    /**
     * @param $blog_id
     * @param $url
     * @param $scheme
     * @param $path
     * @return string
     */
    private static function defaultSiteUrl($blog_id, $url, $scheme, $path): string
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
