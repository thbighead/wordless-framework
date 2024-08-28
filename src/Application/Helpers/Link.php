<?php declare(strict_types=1);

namespace Wordless\Application\Helpers;

use Symfony\Component\Dotenv\Exception\FormatException;
use Wordless\Application\Helpers\Link\Traits\Internal;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Core\Exceptions\DotEnvNotSetException;
use Wordless\Infrastructure\Helper;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;

class Link extends Helper
{
    use Internal;

    /**
     * @param string $css_filename
     * @return string
     * @throws DotEnvNotSetException
     * @throws EmptyConfigKey
     * @throws FormatException
     * @throws PathNotFoundException
     */
    public static function css(string $css_filename): string
    {
        return static::themePublic("css/$css_filename");
    }

    /**
     * @param string $img_filename
     * @return string
     * @throws DotEnvNotSetException
     * @throws EmptyConfigKey
     * @throws FormatException
     * @throws PathNotFoundException
     */
    public static function img(string $img_filename): string
    {
        return static::themePublic("img/$img_filename");
    }

    /**
     * @param string $js_filename
     * @return string
     * @throws DotEnvNotSetException
     * @throws EmptyConfigKey
     * @throws FormatException
     * @throws PathNotFoundException
     */
    public static function js(string $js_filename): string
    {
        return static::themePublic("js/$js_filename");
    }

    /**
     * @param string $additional_path
     * @return string
     * @throws DotEnvNotSetException
     * @throws FormatException
     */
    public static function raw(string $additional_path = ''): string
    {
        return self::getBaseUri() . "/$additional_path";
    }

    /**
     * @param string $additional_path
     * @return string
     * @throws DotEnvNotSetException
     * @throws EmptyConfigKey
     * @throws FormatException
     * @throws PathNotFoundException
     */
    public static function themePublic(string $additional_path = ''): string
    {
        return self::getBaseAssetsUri() . "/public/$additional_path";
    }
}
