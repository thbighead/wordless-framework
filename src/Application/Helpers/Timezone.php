<?php declare(strict_types=1);

namespace Wordless\Application\Helpers;

use Wordless\Application\Commands\ConfigureDateOptions;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\Config\Exceptions\InvalidConfigKey;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Infrastructure\Helper;

class Timezone extends Helper
{
    final public const CONFIG_KEY = 'timezone';
    private const UTC_MARK = 'UTC';

    /**
     * @return string
     * @throws EmptyConfigKey
     * @throws InvalidConfigKey
     * @throws PathNotFoundException
     */
    public static function raw(): string
    {
        return Config::wordpressAdmin()->ofKey(ConfigureDateOptions::CONFIG_KEY_ADMIN_DATETIME)
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
        $gmtOffsetSubject = Str::of(static::raw());

        if (!$gmtOffsetSubject->beginsWith(self::UTC_MARK)) {
            return '';
        }

        return (string)$gmtOffsetSubject->upper()->after(self::UTC_MARK);
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
