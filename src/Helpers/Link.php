<?php

namespace Wordless\Helpers;

use Wordless\Exceptions\PathNotFoundException;

class Link
{
    private static ?string $base_assets_uri = null;

    /**
     * @param string $css_filename
     * @return string
     * @throws PathNotFoundException
     */
    public static function css(string $css_filename): string
    {
        return self::themePublic("css/$css_filename");
    }

    /**
     * @param string $img_filename
     * @return string
     * @throws PathNotFoundException
     */
    public static function img(string $img_filename): string
    {
        return self::themePublic("img/$img_filename");
    }

    /**
     * @param string $js_filename
     * @return string
     * @throws PathNotFoundException
     */
    public static function js(string $js_filename): string
    {
        return self::themePublic("js/$js_filename");
    }

    /**
     * @param string $additional_path
     * @return string
     * @throws PathNotFoundException
     */
    public static function themePublic(string $additional_path = ''): string
    {
        return self::getBaseAssetsUri() . "/public/$additional_path";
    }

    /**
     * @return string
     * @throws PathNotFoundException
     */
    private static function getBaseAssetsUri(): string
    {
        if (static::$base_assets_uri !== null) {
            return static::$base_assets_uri;
        }

        if (function_exists('get_stylesheet_directory_uri')) {
            return static::$base_assets_uri = get_stylesheet_directory_uri();
        }

        return static::$base_assets_uri = self::guessBaseAssetsUri();
    }

    /**
     * @return string
     * @throws PathNotFoundException
     */
    private static function guessBaseAssetsUri(): string
    {
        $base_assets_uri = Environment::get('FRONT_END_URL', '');
        $assets_uri_path = Str::after(ProjectPath::theme(), ProjectPath::wp());

        return "$base_assets_uri$assets_uri_path";
    }
}
