<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Config\Traits;

use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\Config\Traits\Internal\Exceptions\FailedToLoadConfigFile;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Styles\AdminBarEnvironmentFlagStyle\Exceptions\FailedToRetrieveConfigFromWordpressConfigFile;

trait Wordpress
{
    final public const FILE_WORDPRESS = 'wordpress';
    final public const KEY_ADMIN = 'admin';
    final public const KEY_LANGUAGES = 'languages';
    final public const KEY_PERMALINK = 'permalink';
    final public const KEY_THEME = 'theme';

    /**
     * @param string|null $key
     * @param mixed|null $default
     * @return mixed|ConfigSubjectDTO
     */
    public static function wordpress(?string $key = null, mixed $default = null): mixed
    {
        return self::fromConfigFile(self::FILE_WORDPRESS, $key, $default);
    }

    /**
     * @param string|null $key
     * @param mixed|null $default
     * @return mixed|ConfigSubjectDTO
     * @throws FailedToRetrieveConfigFromWordpressConfigFile
     */
    public static function wordpressAdmin(?string $key = null, mixed $default = null): mixed
    {
        return self::fromWordpressFile(self::KEY_ADMIN, $key, $default);
    }

    /**
     * @param string|null $key
     * @param mixed|null $default
     * @return mixed|ConfigSubjectDTO
     * @throws FailedToRetrieveConfigFromWordpressConfigFile
     */
    public static function wordpressLanguages(?string $key = null, mixed $default = null): mixed
    {
        return self::fromWordpressFile(self::KEY_LANGUAGES, $key, $default);
    }

    /**
     * @param string|null $key
     * @param mixed|null $default
     * @return mixed|ConfigSubjectDTO
     * @throws FailedToRetrieveConfigFromWordpressConfigFile
     */
    public static function wordpressTheme(?string $key = null, mixed $default = null): mixed
    {
        return self::fromWordpressFile(self::KEY_THEME, $key, $default);
    }

    /**
     * @param string $ofKey
     * @param string|null $key
     * @param mixed|null $default
     * @return mixed|ConfigSubjectDTO
     * @throws FailedToRetrieveConfigFromWordpressConfigFile
     */
    private static function fromWordpressFile(string $ofKey, ?string $key = null, mixed $default = null): mixed
    {
        try {
            return self::fromDTO(static::wordpress(), $ofKey, $key, $default);
        } catch (EmptyConfigKey|FailedToLoadConfigFile $exception) {
            throw new FailedToRetrieveConfigFromWordpressConfigFile($ofKey, $key, $default, $exception);
        }
    }
}
