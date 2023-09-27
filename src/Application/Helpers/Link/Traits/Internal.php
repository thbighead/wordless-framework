<?php

namespace Wordless\Application\Helpers\Link\Traits;

use Wordless\Application\Helpers\Environment;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;

trait Internal
{
    private static ?string $base_assets_uri = null;

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
