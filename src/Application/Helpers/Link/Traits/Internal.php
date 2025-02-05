<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Link\Traits;

use Symfony\Component\Dotenv\Exception\FormatException;
use Symfony\Component\Dotenv\Exception\PathException;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\Environment;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Core\Exceptions\DotEnvNotSetException;

trait Internal
{
    private static ?string $base_assets_uri = null;
    private static ?string $base_uri = null;

    /**
     * @return string
     * @throws DotEnvNotSetException
     * @throws FormatException
     * @throws PathException
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
     * @throws DotEnvNotSetException
     * @throws FormatException
     * @throws PathException
     */
    private static function guessBaseUri(): string
    {
        return Environment::get('FRONT_END_URL', '');
    }

    /**
     * @return string
     * @throws DotEnvNotSetException
     * @throws EmptyConfigKey
     * @throws FormatException
     * @throws PathException
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
     * @throws DotEnvNotSetException
     * @throws EmptyConfigKey
     * @throws FormatException
     * @throws PathException
     * @throws PathNotFoundException
     */
    private static function guessBaseAssetsUri(): string
    {
        $base_assets_uri = Environment::get('FRONT_END_URL', '');
        $assets_uri_path = Str::after(ProjectPath::theme(), ProjectPath::wp());

        return "$base_assets_uri$assets_uri_path";
    }

    /**
     * @return string
     * @throws DotEnvNotSetException
     * @throws FormatException
     * @throws PathException
     */
    private static function guessUploadsUri(): string
    {
        return self::getBaseUri() . "/wp-content/uploads";
    }
}
