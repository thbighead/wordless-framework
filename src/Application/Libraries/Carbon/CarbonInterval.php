<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\Carbon;

use Carbon\CarbonInterval as OriginalCarbonInterval;
use Carbon\CarbonTimeZone;
use Closure;
use DateInterval;
use Exception;
use Wordless\Application\Helpers\Timezone;

/**
 * @mixin OriginalCarbonInterval
 */
class CarbonInterval
{
    private OriginalCarbonInterval $original;

    /**
     * @param Closure|DateInterval|int|string|null $years
     * @param float|int|null $months
     * @param float|int|null $weeks
     * @param float|int|null $days
     * @param float|int|null $hours
     * @param float|int|null $minutes
     * @param float|int|null $seconds
     * @param float|int|null $microseconds
     * @throws Exception
     */
    public function __construct(
        Closure|DateInterval|int|null|string $years,
        float|int|null                       $months,
        float|int|null                       $weeks,
        float|int|null                       $days,
        float|int|null                       $hours,
        float|int|null                       $minutes,
        float|int|null                       $seconds,
        float|int|null                       $microseconds
    )
    {
        $this->original = (new OriginalCarbonInterval(
            $years,
            $months,
            $weeks,
            $days,
            $hours,
            $minutes,
            $seconds,
            $microseconds
        ))->setTimezone(wp_timezone());
    }

    public function __call(string $name, array $arguments)
    {
        return $this->original->$name(...$arguments);
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws Exception
     */
    public static function __callStatic(string $name, array $arguments)
    {
        if ((new CarbonTimeZone(Timezone::forPhpIni()))->getName() !== $timezone = wp_timezone_string()) {
            date_default_timezone_set($timezone);
        }

        return OriginalCarbonInterval::$name(...$arguments);
    }
}
