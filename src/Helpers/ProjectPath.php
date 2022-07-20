<?php

namespace Wordless\Helpers;

use Wordless\Exceptions\PathNotFoundException;

class ProjectPath
{
    public const VENDOR_PACKAGE_PROJECT = 'thbighead/wordless';
    public const VENDOR_PACKAGE_RELATIVE_PATH = self::VENDOR_PACKAGE_PROJECT . '-framework';
    private const SLASH = '/';

    /**
     * @param string $additional_path
     * @return string
     * @throws PathNotFoundException
     */
    public static function app(string $additional_path = ''): string
    {
        return self::root("app/$additional_path");
    }

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
    public static function commands(string $additional_path = ''): string
    {
        $relative_path = "Commands/$additional_path";

        try {
            return self::src($relative_path);
        } catch (PathNotFoundException $exception) {
            return self::app($relative_path);
        }
    }

    /**
     * @param string $additional_path
     * @return string
     * @throws PathNotFoundException
     */
    public static function config(string $additional_path = ''): string
    {
        $relative_path = "config/$additional_path";

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
    public static function controllers(string $additional_path = ''): string
    {
        return self::app("Controllers/$additional_path");
    }

    /**
     * @param string $additional_path
     * @return string
     * @throws PathNotFoundException
     */
    public static function migrations(string $additional_path = ''): string
    {
        return self::root("migrations/$additional_path");
    }

    /**
     * @param string $additional_path
     * @return string
     * @throws PathNotFoundException
     */
    public static function public(string $additional_path = ''): string
    {
        return self::root("public/$additional_path");
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
        return self::vendorPackageRoot("src/$additional_path");
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
    public static function theme(string $additional_path = ''): string
    {
        return self::wpThemes(Environment::get('WP_THEME', 'wordless') . "/$additional_path");
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
    public static function vendorPackageRoot(string $additional_path = ''): string
    {
        return self::vendor(self::VENDOR_PACKAGE_RELATIVE_PATH . "/$additional_path");
    }

    /**
     * @param string $additional_path
     * @return string
     * @throws PathNotFoundException
     */
    public static function wp(string $additional_path = ''): string
    {
        return self::root("wp/$additional_path");
    }

    /**
     * @param string $additional_path
     * @return string
     * @throws PathNotFoundException
     */
    public static function wpCore(string $additional_path = ''): string
    {
        return self::wp("wp-core/$additional_path");
    }

    /**
     * @param string $additional_path
     * @return string
     * @throws PathNotFoundException
     */
    public static function wpContent(string $additional_path = ''): string
    {
        return self::wp("wp-content/$additional_path");
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
     * @param string $additional_path
     * @return string
     * @throws PathNotFoundException
     */
    public static function wpPlugins(string $additional_path = ''): string
    {
        return self::wpContent("plugins/$additional_path");
    }

    /**
     * @param string $additional_path
     * @return string
     * @throws PathNotFoundException
     */
    public static function wpThemes(string $additional_path = ''): string
    {
        return self::wpContent("themes/$additional_path");
    }

    /**
     * @param string $additional_path
     * @return string
     * @throws PathNotFoundException
     */
    public static function wpUploads(string $additional_path = ''): string
    {
        return self::wpContent("uploads/$additional_path");
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