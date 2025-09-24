<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Link\Traits;

use Wordless\Application\Helpers\Environment;
use Wordless\Application\Helpers\Environment\Exceptions\CannotResolveEnvironmentGet;
use Wordless\Application\Helpers\Link\Traits\Internal\Exceptions\FailedToGuessBaseAssetsUri;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\FailedToGetWordpressTheme;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;

trait Internal
{
    private static ?string $base_assets_uri = null;
    private static ?string $base_uri = null;

    /**
     * @return string
     * @throws CannotResolveEnvironmentGet
     */
    private static function getBaseUri(): string
    {
        if (static::$base_uri !== null) {
            return static::$base_uri;
        }

        if (function_exists('home_url')) {
            return static::$base_uri = home_url();
        }

        return static::$base_uri = self::guessBaseUri();
    }

    /**
     * @return string
     * @throws CannotResolveEnvironmentGet
     */
    private static function guessBaseUri(): string
    {
        return Environment::get('FRONT_END_URL', '');
    }

    /**
     * @return string
     * @throws FailedToGuessBaseAssetsUri
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
     * @throws FailedToGuessBaseAssetsUri
     */
    private static function guessBaseAssetsUri(): string
    {
        try {
            $base_assets_uri = Environment::get('FRONT_END_URL', '');
            $assets_uri_path = Str::after(ProjectPath::theme(), ProjectPath::wp());

            return "$base_assets_uri$assets_uri_path";
        } catch (CannotResolveEnvironmentGet|PathNotFoundException|FailedToGetWordpressTheme $exception) {
            throw new FailedToGuessBaseAssetsUri($exception);
        }
    }

    /**
     * @return string
     * @throws CannotResolveEnvironmentGet
     */
    private static function guessUploadsUri(): string
    {
        return self::getBaseUri() . '/wp-content/uploads';
    }
}
