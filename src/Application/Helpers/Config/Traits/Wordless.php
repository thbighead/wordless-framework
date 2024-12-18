<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Config\Traits;

use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Infrastructure\Http\Security\Cors;
use Wordless\Infrastructure\Http\Security\Csp;

trait Wordless
{
    final public const FILE_WORDLESS = 'wordless';
    final public const KEY_DATABASE = 'database';
    final public const KEY_PLUGINS_ORDER = 'plugins_order';

    /**
     * @param string|null $key
     * @param mixed|null $default
     * @return mixed|ConfigSubjectDTO
     * @throws PathNotFoundException
     */
    public static function wordless(?string $key = null, mixed $default = null): mixed
    {
        return self::fromConfigFile(self::FILE_WORDLESS, $key, $default);
    }

    /**
     * @param string|null $key
     * @param mixed|null $default
     * @return ConfigSubjectDTO|mixed
     * @throws EmptyConfigKey
     * @throws PathNotFoundException
     */
    public static function wordlessCors(?string $key = null, mixed $default = null): mixed
    {
        return self::fromWordlessFile(Cors::CONFIG_KEY, $key, $default);
    }

    /**
     * @param string|null $key
     * @param mixed|null $default
     * @return ConfigSubjectDTO|mixed
     * @throws EmptyConfigKey
     * @throws PathNotFoundException
     */
    public static function wordlessCsp(?string $key = null, mixed $default = null): mixed
    {
        return self::fromWordlessFile(Csp::CONFIG_KEY, $key, $default);
    }

    /**
     * @param string|null $key
     * @param mixed|null $default
     * @return ConfigSubjectDTO|mixed
     * @throws EmptyConfigKey
     * @throws PathNotFoundException
     */
    public static function wordlessDatabase(?string $key = null, mixed $default = null): mixed
    {
        return self::fromWordlessFile(self::KEY_DATABASE, $key, $default);
    }

    /**
     * @return string[]
     * @throws EmptyConfigKey
     * @throws PathNotFoundException
     */
    public static function wordlessPluginsOrder(): array
    {
        return self::fromWordlessFile(self::KEY_PLUGINS_ORDER)->get(default: []);
    }

    /**
     * @param string $ofKey
     * @param string|null $key
     * @param mixed|null $default
     * @return mixed|ConfigSubjectDTO
     * @throws EmptyConfigKey
     * @throws PathNotFoundException
     */
    private static function fromWordlessFile(string $ofKey, ?string $key = null, mixed $default = null): mixed
    {
        return self::fromDTO(static::wordless(), $ofKey, $key, $default);
    }
}
