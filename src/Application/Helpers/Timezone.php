<?php declare(strict_types=1);

namespace Wordless\Application\Helpers;

use Wordless\Application\Commands\ConfigureDateOptions;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\Config\Exceptions\InvalidConfigKey;
use Wordless\Application\Helpers\Config\Traits\Internal\Exceptions\FailedToLoadConfigFile;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Styles\AdminBarEnvironmentFlagStyle\Exceptions\FailedToRetrieveConfigFromWordpressConfigFile;
use Wordless\Infrastructure\Helper;

class Timezone extends Helper
{
    final public const CONFIG_KEY = 'timezone';
    private const UTC_MARK = 'UTC';

    /**
     * @return string
     * @throws FailedToRetrieveConfigFromWordpressConfigFile
     */
    public static function raw(): string
    {
        $of_key = ConfigureDateOptions::CONFIG_KEY_ADMIN_DATETIME;
        $key = self::CONFIG_KEY;

        try {
            return Config::wordpressAdmin()->ofKey($of_key)->getOrFail($key);
        } catch (EmptyConfigKey|InvalidConfigKey|FailedToLoadConfigFile $exception) {
            throw new FailedToRetrieveConfigFromWordpressConfigFile($of_key, $key, $exception);
        }
    }

    /**
     * @return string
     * @throws FailedToRetrieveConfigFromWordpressConfigFile
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
     * @throws FailedToRetrieveConfigFromWordpressConfigFile
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
     * @throws FailedToRetrieveConfigFromWordpressConfigFile
     */
    public static function forPhpIni(): string
    {
        if (empty($gmt_offset = static::forOptionGmtOffset())) {
            return static::raw();
        }

        $gmt_offset = ((int)$gmt_offset) * -1;

        if ($gmt_offset >= 0) {
            $gmt_offset = "+$gmt_offset";
        }

        return "Etc/GMT$gmt_offset";
    }
}
