<?php declare(strict_types=1);

namespace Wordless\Application\Helpers;

use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\Config\Exceptions\InvalidConfigKey;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;

class Timezone
{
    final public const CONFIG_KEY = 'timezone';

    /**
     * @return string
     * @throws EmptyConfigKey
     * @throws InvalidConfigKey
     * @throws PathNotFoundException
     */
    public static function raw(): string
    {
        return Config::wordpressAdmin()->ofKey('datetime')
            ->getOrFail(self::CONFIG_KEY);
    }

    /**
     * @return string
     * @throws EmptyConfigKey
     * @throws InvalidConfigKey
     * @throws PathNotFoundException
     */
    public static function forOptionGmtOffset(): string
    {
        return (string)Str::of(static::raw())->upper()->after('UTC');
    }

    /**
     * @return string
     * @throws EmptyConfigKey
     * @throws InvalidConfigKey
     * @throws PathNotFoundException
     */
    public static function forOptionTimezoneString(): string
    {
        if (!empty(static::forOptionGmtOffset())) {
            return '';
        }

        return static::raw();
    }

    /**
     * @return string
     * @throws EmptyConfigKey
     * @throws InvalidConfigKey
     * @throws PathNotFoundException
     */
    public static function forPhpIni(): string
    {
        if (empty($gmt_offset = static::forOptionGmtOffset())) {
            return static::raw();
        }

        return "Etc/GMT$gmt_offset";
    }
}
