<?php

namespace Wordless\Helpers;

use Wordless\Exception\PathNotFoundException;

class ProjectPath
{
    private const SLASH = '/';

    /**
     * @param string $additional_path
     * @return string
     * @throws PathNotFoundException
     */
    public static function cache(string $additional_path = ''): string
    {
        return self::root("cache/$additional_path");
    }

    /**
     * @param string $additional_path
     * @return string
     * @throws PathNotFoundException
     */
    public static function controllers(string $additional_path = ''): string
    {
        return self::root("Controllers/$additional_path");
    }

    /**
     * @param string $additional_path
     * @return string
     * @throws PathNotFoundException
     */
    public static function publicHtml(string $additional_path = ''): string
    {
        return self::root("public_html/$additional_path");
    }

    /**
     * @param string $full_path
     * @return string
     * @throws PathNotFoundException
     */
    public static function realpath(string $full_path): string
    {
        if (($real_path = realpath($full_path)) === false) {
            throw new PathNotFoundException($full_path);
        }

        return $real_path;
    }

    /**
     * @param string $additional_path
     * @return string
     * @throws PathNotFoundException
     */
    public static function root(string $additional_path = ''): string
    {
        return self::full($additional_path);
    }

    /**
     * @param string $additional_path
     * @return string
     * @throws PathNotFoundException
     */
    public static function src(string $additional_path = ''): string
    {
        return self::vendor("wordless-framework/src/$additional_path");
    }

    /**
     * @param string $additional_path
     * @return string
     * @throws PathNotFoundException
     */
    public static function stubs(string $additional_path = ''): string
    {
        $relative_path = "stubs/$additional_path";

        try {
            return self::root($relative_path);
        } catch (PathNotFoundException $exception) {
            return self::src($relative_path);
        }
    }

    /**
     * @param string $additional_path
     * @return string
     * @throws PathNotFoundException
     */
    public static function vendor(string $additional_path = ''): string
    {
        return self::root("vendor/$additional_path");
    }

    /**
     * @param string $additional_path
     * @return string
     * @throws PathNotFoundException
     */
    public static function wpCms(string $additional_path = ''): string
    {
        return self::publicHtml("wp-cms/$additional_path");
    }

    /**
     * @param string $additional_path
     * @return string
     * @throws PathNotFoundException
     */
    public static function wpCore(string $additional_path = ''): string
    {
        return self::wpCms("wp-core/$additional_path");
    }

    /**
     * @param string $additional_path
     * @return string
     * @throws PathNotFoundException
     */
    public static function wpContent(string $additional_path = ''): string
    {
        return self::wpCms("wp-content/$additional_path");
    }

    /**
     * @param string $additional_path
     * @return string
     * @throws PathNotFoundException
     */
    public static function wpMustUsePlugins(string $additional_path = ''): string
    {
        return self::wpContent("mu-plugins/$additional_path");
    }

    /**
     * @param string $path
     * @return string
     * @throws PathNotFoundException
     */
    private static function full(string $path = ''): string
    {
        return self::realpath(ROOT_PROJECT_PATH . self::SLASH . trim($path, self::SLASH));
    }
}