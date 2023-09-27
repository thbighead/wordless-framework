<?php

namespace Wordless\Application\Helpers\Http\Traits;

use WP_Http;

trait Internal
{
    private static WP_Http $wp_http;

    private static function getWpHttp(): WP_Http
    {
        if (isset(self::$wp_http)) {
            return self::$wp_http;
        }

        return self::$wp_http = new WP_Http;
    }
}
