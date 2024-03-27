<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Config\Traits;

use Wordless\Application\Helpers\Arr;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\Config\Exceptions\InvalidConfigKey;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;

trait Internal
{
    private const SEPARATOR = '.';
    private const WILDCARD = '*';

    private static array $configs = [];

    /**
     * @param string $filename
     * @param string|null $key
     * @param mixed $default
     * @return mixed|ConfigSubjectDTO
     * @throws PathNotFoundException
     */
    private static function fromConfigFile(string $filename, ?string $key = null, mixed $default = null): mixed
    {
        $config = static::of($filename);

        if ($key === null) {
            return $config;
        }

        return $config->get($key, $default);
    }

    /**
     * @param ConfigSubjectDTO $dto
     * @param string $ofKey
     * @param string|null $key
     * @param mixed|null $default
     * @return mixed|ConfigSubjectDTO
     * @throws EmptyConfigKey
     * @throws PathNotFoundException
     */
    private static function fromDTO(
        ConfigSubjectDTO $dto,
        string $ofKey,
        ?string $key = null,
        mixed $default = null
    ): mixed
    {
        $config = $dto->ofKey($ofKey);

        if ($key === null) {
            return $config;
        }

        return $config->get($key, $default);
    }

    /**
     * @param string $config_filename_without_extension
     * @return mixed
     * @throws PathNotFoundException
     */
    private static function loadConfigFile(string $config_filename_without_extension): mixed
    {
        return require ProjectPath::config("$config_filename_without_extension.php");
    }

    /**
     * @param string $keys_as_string
     * @return array
     * @throws InvalidConfigKey
     */
    private static function mountKeys(string $keys_as_string): array
    {
        $keys = explode(self::SEPARATOR, $keys_as_string);

        if (empty($keys)) {
            throw new InvalidConfigKey($keys_as_string);
        }

        return $keys;
    }

    /**
     * @param array $keys
     * @return mixed
     * @throws PathNotFoundException
     */
    private static function retrieveConfig(array &$keys): mixed
    {
        $config_filename_without_extension = array_shift($keys);

        return self::$configs[$config_filename_without_extension] ??
            self::$configs[$config_filename_without_extension] = self::loadConfigFile(
                $config_filename_without_extension
            );
    }

    /**
     * @param array $keys
     * @param array $config
     * @param string $original_keys_as_string
     * @return mixed
     * @throws InvalidConfigKey
     */
    private static function searchKeysIntoConfig(array $keys, array $config, string $original_keys_as_string): mixed
    {
        $pointer = $config;

        foreach ($keys as $parsed_key) {
            if (!is_array($pointer) || !key_exists($parsed_key, $pointer)) {
                throw new InvalidConfigKey($original_keys_as_string);
            }

            $pointer = $pointer[$parsed_key];
        }

        return $pointer;
    }
}
