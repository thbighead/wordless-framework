<?php declare(strict_types=1);

namespace Wordless\Application\Listeners\CustomAdminUrl;

use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Listeners\CustomAdminUrl\Contracts\BaseListener;
use Wordless\Infrastructure\Wordpress\Hook\Contracts\FilterHook;
use Wordless\Infrastructure\Wordpress\Listener\FilterListener\Traits\Adapter as FilterListener;
use Wordless\Wordpress\Hook\Enums\Filter;

class SiteUrlCustomLoginUrl extends BaseListener
{
    use FilterListener;

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
