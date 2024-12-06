<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\Carbon;

use Carbon\CarbonTimeZone as OriginalCarbonTimeZone;
use Exception;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Timezone;

/**
 * @mixin OriginalCarbonTimeZone
 */
class CarbonTimeZone
{
    private OriginalCarbonTimeZone $original;

    /**
     * @param string|null $timezone
     * @throws PathNotFoundException
     * @throws Exception
     */
    public function __construct(?string $timezone = null)
    {
        $this->original = new OriginalCarbonTimeZone($timezone ?? Timezone::forPhpIni());
    }

    public function __call(string $name, array $arguments)
    {
        return $this->original->$name(...$arguments);
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws PathNotFoundException
     * @throws Exception
     */
    public static function __callStatic(string $name, array $arguments)
    {
        if ((new OriginalCarbonTimeZone(Timezone::forPhpIni()))->getName() !== $timezone = wp_timezone_string()) {
            date_default_timezone_set($timezone);
        }

        return OriginalCarbonTimeZone::$name(...$arguments);
    }
}
