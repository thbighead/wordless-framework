<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\Carbon;

use Carbon\CarbonPeriod as OriginalCarbonPeriod;
use Carbon\CarbonPeriodImmutable as OriginalCarbonPeriodImmutable;
use Carbon\CarbonTimeZone;
use DateTimeZone;
use Exception;
use InvalidArgumentException;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Timezone;

/**
 * @mixin OriginalCarbonPeriodImmutable
 */
class CarbonPeriodImmutable
{
    private OriginalCarbonPeriodImmutable $original;

    /**
     * @param DateTimeZone|null $timezone
     * @param ...$arguments
     * @throws InvalidArgumentException
     */
    public function __construct(?DateTimeZone $timezone = null, ...$arguments)
    {
        $this->original = new OriginalCarbonPeriodImmutable(...$arguments, $timezone ?? wp_timezone());
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
        if ((new CarbonTimeZone(Timezone::forPhpIni()))->getName() !== $timezone = wp_timezone_string()) {
            date_default_timezone_set($timezone);
        }

        return OriginalCarbonPeriod::$name(...$arguments);
    }
}
