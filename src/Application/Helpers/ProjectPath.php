<?php declare(strict_types=1);

namespace Wordless\Application\Helpers;

use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToGetCurrentWorkingDirectory;
use Wordless\Application\Helpers\ProjectPath\Contracts\Subjectable;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\ProjectPath\Traits\Internal;

class ProjectPath extends Subjectable
{
    use Internal;

    final public const VENDOR_PACKAGE_PROJECT = 'thbighead/wordless';
    final public const VENDOR_PACKAGE_RELATIVE_PATH = self::VENDOR_PACKAGE_PROJECT . '-framework';

    /**
     * @param string $additional_path
     * @return string
     * @throws PathNotFoundException
     */
    final public static function app(string $additional_path = ''): string
    {
        return self::root("app/$additional_path");
    }

    /**
     * @param string $additional_path
     * @return string
     * @throws PathNotFoundException
     */
    final public static function assets(string $additional_path = ''): string
    {
        return self::vendorPackageRoot("assets/$additional_path");
    }

    /**
     * @param string $additional_path
     * @return string
     * @throws PathNotFoundException
     */
    final public static function cache(string $additional_path = ''): string
    {
        return self::root("cache/$additional_path");
    }

    /**
     * @param string $additional_path
     * @return string
     * @throws PathNotFoundException
     */
    final public static function commands(string $additional_path = ''): string
    {
        $relative_path = "Commands/$additional_path";

        try {
            return self::src($relative_path);
        } catch (PathNotFoundException) {
            return self::app($relative_path);
        }
    }

    /**
     * @param string $additional_path
     * @return string
     * @throws PathNotFoundException
     */
    final public static function config(string $additional_path = ''): string
    {
        $relative_path = "config/$additional_path";

        try {
            return self::root($relative_path);
        } catch (PathNotFoundException) {
            return self::assets($relative_path);
        }
    }

    /**
     * @param string $additional_path
     * @return string
     * @throws PathNotFoundException
     */
    final public static function controllers(string $additional_path = ''): string
    {
        return self::app("Controllers/$additional_path");
    }

    /**
     * @param string $additional_path
     * @return string
     * @throws PathNotFoundException
     */
    final public static function customPostTypes(string $additional_path = ''): string
    {
        return self::app("CustomPostTypes/$additional_path");
    }

    /**
     * @param string $additional_path
     * @return string
     * @throws PathNotFoundException
     */
    final public static function dist(string $additional_path = ''): string
    {
        return self::vendorPackageRoot("dist/$additional_path");
    }

    /**
     * @param string $additional_path
     * @return string
     * @throws PathNotFoundException
     */
    final public static function docker(string $additional_path = ''): string
    {
        return self::root("docker/$additional_path");
    }

    /**
     * @param string $additional_path
     * @return string
     * @throws PathNotFoundException
     */
    final public static function listeners(string $additional_path = ''): string
    {
        return self::app("Listeners/$additional_path");
    }

    /**
     * @param string $additional_path
     * @return string
     * @throws PathNotFoundException
     */
    final public static function migrations(string $additional_path = ''): string
    {
        return self::root("migrations/$additional_path");
    }

    /**
     * @param string $full_path
     * @return string
     * @throws PathNotFoundException
     */
    final public static function path(string $full_path): string
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
    final public static function providers(string $additional_path = ''): string
    {
        $relative_path = "Providers/$additional_path";

        try {
            return self::app($relative_path);
        } catch (PathNotFoundException) {
            return self::srcApplication($relative_path);
        }
    }

    /**
     * @param string $additional_path
     * @return string
     * @throws PathNotFoundException
     */
    final public static function public(string $additional_path = ''): string
    {
        return self::root("public/$additional_path");
    }

    /**
     * @param string $full_path
     * @return string
     * @throws PathNotFoundException
     */
    final public static function realpath(string $full_path): string
    {
        if (is_link($full_path)) {
            $real_path = self::realpath(dirname($full_path))
                . DIRECTORY_SEPARATOR
                . Str::afterLast($full_path, self::SLASH);

            if (!is_link($real_path)) {
                throw new PathNotFoundException($full_path);
            }

            return $real_path;
        }

        return self::path($full_path);
    }

    /**
     * @param string $to_absolute_path
     * @param string|null $from_absolute_path
     * @return string
     * @throws FailedToGetCurrentWorkingDirectory
     * @throws PathNotFoundException
     */
    final public static function relativeTo(string $to_absolute_path, ?string $from_absolute_path = null): string
    {
        $to_absolute_path = self::realpath($to_absolute_path);
        $from_absolute_path = $from_absolute_path
            ? self::realpath($from_absolute_path)
            : DirectoryFiles::getCurrentWorkingDirectory();

        $exploded_to_path = explode(
            DIRECTORY_SEPARATOR,
            rtrim($to_absolute_path, DIRECTORY_SEPARATOR)
        );

        $exploded_from_path = explode(
            DIRECTORY_SEPARATOR,
            rtrim($from_absolute_path, DIRECTORY_SEPARATOR)
        );

        $match_count = 0;

        for ($i = 0; $i < min(count($exploded_from_path), count($exploded_to_path)); $i++) {
            if ($exploded_from_path[$i] === $exploded_to_path[$i]) {
                $match_count++;
            }
        }

        $relativePath = str_repeat('..' . DIRECTORY_SEPARATOR, count($exploded_from_path) - $match_count)
            . implode(DIRECTORY_SEPARATOR, array_slice($exploded_to_path, $match_count));

        return $relativePath === '' ? '.' : $relativePath;
    }

    /**
     * @param string $additional_path
     * @return string
     * @throws PathNotFoundException
     */
    final public static function root(string $additional_path = ''): string
    {
        return self::full($additional_path);
    }

    /**
     * @param string $additional_path
     * @return string
     * @throws PathNotFoundException
     */
    final public static function schedulers(string $additional_path = ''): string
    {
        return self::app("Schedulers/$additional_path");
    }

    /**
     * @param string $additional_path
     * @return string
     * @throws PathNotFoundException
     */
    final public static function scripts(string $additional_path = ''): string
    {
        return self::app("Scripts/$additional_path");
    }

    /**
     * @param string $additional_path
     * @return string
     * @throws PathNotFoundException
     */
    final public static function src(string $additional_path = ''): string
    {
        return self::vendorPackageRoot("src/$additional_path");
    }

    /**
     * @param string $additional_path
     * @return string
     * @throws PathNotFoundException
     */
    final public static function srcApplication(string $additional_path = ''): string
    {
        return self::src("Application/$additional_path");
    }

    /**
     * @param string $additional_path
     * @return string
     * @throws PathNotFoundException
     */
    final public static function stubs(string $additional_path = ''): string
    {
        $relative_path = "stubs/$additional_path";

        try {
            return self::root($relative_path);
        } catch (PathNotFoundException) {
            return self::assets($relative_path);
        }
    }

    /**
     * @param string $additional_path
     * @return string
     * @throws PathNotFoundException
     */
    final public static function styles(string $additional_path = ''): string
    {
        return self::app("Styles/$additional_path");
    }

    /**
     * @param string $additional_path
     * @return string
     * @throws EmptyConfigKey
     * @throws PathNotFoundException
     */
    final public static function theme(string $additional_path = ''): string
    {
        return self::wpThemes(Config::wordpressTheme()->get(default: 'wordless')
            . "/$additional_path");
    }

    /**
     * @param string $additional_path
     * @return string
     * @throws PathNotFoundException
     */
    final public static function vendor(string $additional_path = ''): string
    {
        return self::root("vendor/$additional_path");
    }

    /**
     * @param string $additional_path
     * @return string
     * @throws PathNotFoundException
     */
    final public static function vendorPackageRoot(string $additional_path = ''): string
    {
        return Environment::isFramework() ?
            self::root($additional_path) :
            self::vendor(self::VENDOR_PACKAGE_RELATIVE_PATH . "/$additional_path");
    }

    /**
     * @param string $additional_path
     * @return string
     * @throws PathNotFoundException
     */
    final public static function wp(string $additional_path = ''): string
    {
        return self::root("wp/$additional_path");
    }

    /**
     * @param string $additional_path
     * @return string
     * @throws PathNotFoundException
     */
    final public static function wpCore(string $additional_path = ''): string
    {
        return self::wp("wp-core/$additional_path");
    }

    /**
     * @param string $additional_path
     * @return string
     * @throws PathNotFoundException
     */
    final public static function wpContent(string $additional_path = ''): string
    {
        return self::wp("wp-content/$additional_path");
    }

    /**
     * @param string $additional_path
     * @return string
     * @throws PathNotFoundException
     */
    final public static function wpMustUsePlugins(string $additional_path = ''): string
    {
        return self::wpContent("mu-plugins/$additional_path");
    }

    /**
     * @param string $additional_path
     * @return string
     * @throws PathNotFoundException
     */
    final public static function wpPlugins(string $additional_path = ''): string
    {
        return self::wpContent("plugins/$additional_path");
    }

    /**
     * @param string $additional_path
     * @return string
     * @throws PathNotFoundException
     */
    final public static function wpThemes(string $additional_path = ''): string
    {
        return self::wpContent("themes/$additional_path");
    }

    /**
     * @param string $additional_path
     * @return string
     * @throws PathNotFoundException
     */
    final public static function wpUploads(string $additional_path = ''): string
    {
        try {
            $wordpress_try = wp_get_upload_dir()['basedir'] ?? self::wpContent('uploads');
        } catch (PathNotFoundException $exception) {
            throw new PathNotFoundException("$exception->path/$additional_path", $exception);
        }

        return self::realpath("$wordpress_try/$additional_path");
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
