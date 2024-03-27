<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Config\Traits;

use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;

trait Wordless
{
    final public const FILE_WORDLESS = 'wordless';
    final public const KEY_CSP = 'csp';
    final public const KEY_DATABASE = 'database';

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
    public static function wordlessCsp(?string $key = null, mixed $default = null): mixed
    {
        return self::fromWordlessFile(self::KEY_CSP, $key, $default);
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
