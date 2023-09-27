<?php

namespace Wordless\Application\Helpers;

use Wordless\Application\Helpers\Link\Traits\Internal;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;

class Link
{
    use Internal;

    /**
     * @param string $css_filename
     * @return string
     * @throws PathNotFoundException
     */
    public static function css(string $css_filename): string
    {
        return static::themePublic("css/$css_filename");
    }

    /**
     * @param string $img_filename
     * @return string
     * @throws PathNotFoundException
     */
    public static function img(string $img_filename): string
    {
        return static::themePublic("img/$img_filename");
    }

    /**
     * @param string $js_filename
     * @return string
     * @throws PathNotFoundException
     */
    public static function js(string $js_filename): string
    {
        return static::themePublic("js/$js_filename");
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
}
